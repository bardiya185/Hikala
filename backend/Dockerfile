
FROM php:8.3-fpm-alpine AS base

WORKDIR /var/www/html


RUN apk add --no-cache \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    oniguruma-dev \
    $PHPIZE_DEPS

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


FROM base AS deps

COPY composer.json composer.lock ./
# خط ۲۵ را به این شکل اصلاح کن:
RUN composer install --no-interaction --no-plugins --no-scripts --prefer-dist --ignore-platform-reqs


FROM deps AS dev

COPY . .
RUN composer install --no-interaction

EXPOSE 8000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]


FROM deps AS build

COPY . .

RUN composer install --no-dev --no-interaction --no-plugins --no-scripts --optimize-autoloader


FROM base AS final

ENV NODE_ENV=production


COPY --from=build /var/www/html /var/www/html


RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]