# ---------- Laravel stage ----------
FROM php:8.3-fpm-alpine AS phpbase

WORKDIR /var/www/html

RUN apk add --no-cache \
    curl nginx supervisor mysql-client \
    libpng-dev libxml2-dev oniguruma-dev icu-dev zip unzip git \
    $PHPIZE_DEPS \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY backend/composer.json backend/composer.lock ./
RUN composer install --no-interaction --no-plugins --no-scripts --prefer-dist --ignore-platform-reqs

COPY backend/ .
RUN composer install --no-dev --no-interaction --no-plugins --no-scripts --optimize-autoloader \
    && composer dump-autoload --classmap-authoritative --no-dev --no-scripts


# ---------- Next.js stage ----------
FROM node:22-alpine AS webdeps
WORKDIR /app
ENV CI=true

RUN corepack enable && corepack prepare pnpm@10.34.5 --activate

COPY frontend/package.json frontend/pnpm-lock.yaml frontend/pnpm-workspace.yaml ./
RUN pnpm install --frozen-lockfile

FROM webdeps AS webbuild
WORKDIR /app

COPY frontend/ .

ARG NEXT_PUBLIC_BASE_URL
ARG NEXT_PUBLIC_NESHAN_API_KEY
ENV NEXT_PUBLIC_BASE_URL=$NEXT_PUBLIC_BASE_URL \
    NEXT_PUBLIC_NESHAN_API_KEY=$NEXT_PUBLIC_NESHAN_API_KEY \
    NEXT_TELEMETRY_DISABLED=1

RUN pnpm build


# ---------- Combined runtime ----------
FROM phpbase AS production

ENV NODE_ENV=production \
    NEXT_TELEMETRY_DISABLED=1

RUN apk add --no-cache nodejs

COPY docker/php.ini /usr/local/etc/php/conf.d/99-app.ini
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
COPY docker/nginx/default.conf.template /etc/nginx/http.d/default.conf.template
COPY docker/supervisord.conf /etc/supervisord.conf
RUN chmod +x /usr/local/bin/entrypoint.sh

# Laravel lives at /var/www/html (already built in the phpbase stage)
RUN mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/framework/testing bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Next.js standalone
ENV NEXTDIR=/app/frontend
WORKDIR $NEXTDIR
COPY --from=webbuild /app/.next/standalone ./
COPY --from=webbuild /app/.next/static ./.next/static
COPY --from=webbuild /app/public ./public

EXPOSE 8080
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["supervisord", "-c", "/etc/supervisord.conf"]
