#!/bin/sh
set -e

cd /var/www/html

: "${PORT:=8080}"
export PORT

# Next.js server-side fetches hit Laravel through nginx inside this container.
: "${SERVER_BASE_URL:=http://127.0.0.1:${PORT}}"
export SERVER_BASE_URL

# Render nginx site config with the port Railway assigns.
sed "s/__PORT__/${PORT}/g" /etc/nginx/http.d/default.conf.template > /etc/nginx/http.d/default.conf

# Laravel needs an APP_KEY; generate one if Railway didn't provide it.
if [ -z "$APP_KEY" ]; then
    [ -f .env ] || cp .env.example .env
    php artisan key:generate --force --no-interaction || true
fi

if [ "$RUN_MIGRATIONS" != "false" ]; then
    if [ "${DB_CONNECTION:-mysql}" != "sqlite" ]; then
        echo "Waiting for database at ${DB_HOST:-127.0.0.1}:${DB_PORT:-3306} ..."
        php -r '
$host = getenv("DB_HOST") ?: "127.0.0.1";
$port = (int) (getenv("DB_PORT") ?: 3306);
for ($i = 0; $i < 60; $i++) {
    if (@fsockopen($host, $port, $errno, $errstr, 2)) { exit(0); }
    sleep(1);
}
fwrite(STDERR, "Database never became reachable\n");
exit(1);
'
    else
        touch "${DB_DATABASE:-database/database.sqlite}"
    fi

    php artisan migrate --force --no-interaction

    if [ "$SEED_DATABASE" = "true" ]; then
        php artisan db:seed --force --no-interaction
    fi

    if [ "$DEDUPE_PRODUCTS" = "true" ]; then
        echo "Deduping duplicate products..."
        php artisan products:dedupe --no-interaction || true
    fi
fi

php artisan storage:link --force --no-interaction || true
php artisan config:cache --no-interaction || true
php artisan route:cache --no-interaction || true
php artisan view:cache --no-interaction || true

exec "$@"
