FROM php:8.3-fpm-alpine AS base

WORKDIR /var/www/html

RUN apk add --no-cache \
    curl \
    nginx \
    supervisor \
    mysql-client \
    libpng-dev \
    libxml2-dev \
    oniguruma-dev \
    icu-dev \
    zip \
    unzip \
    git \
    $PHPIZE_DEPS \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer


FROM base AS deps

COPY composer.json composer.lock ./
RUN composer install --no-interaction --no-plugins --no-scripts --prefer-dist --ignore-platform-reqs


FROM deps AS dev

COPY . .
RUN composer install --no-interaction --no-scripts \
    && composer dump-autoload --optimize --no-scripts

EXPOSE 8000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]


FROM deps AS build

COPY . .
RUN composer install --no-dev --no-interaction --no-plugins --no-scripts --optimize-autoloader \
    && composer dump-autoload --classmap-authoritative --no-dev --no-scripts


FROM base AS production

ENV NODE_ENV=production

COPY docker/php.ini /usr/local/etc/php/conf.d/99-app.ini
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
COPY docker/nginx/default.conf.template /etc/nginx/http.d/default.conf.template
COPY docker/supervisord.conf /etc/supervisord.conf
RUN chmod +x /usr/local/bin/entrypoint.sh

COPY --from=build /var/www/html /var/www/html

RUN mkdir -p storage/framework/{cache,sessions,views,testing} bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 8080
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["supervisord", "-c", "/etc/supervisord.conf"]
