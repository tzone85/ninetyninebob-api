# syntax=docker/dockerfile:1.7

# Stage 1: composer install (cacheable)
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
COPY database/migrations database/migrations
COPY database/factories database/factories
COPY database/seeders database/seeders
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-progress

# Stage 2: PHP-FPM runtime
FROM php:8.3-fpm-alpine AS runtime

RUN apk add --no-cache nginx supervisor curl bash icu-dev libpng-dev libxml2-dev libzip-dev oniguruma-dev \
    postgresql-dev postgresql-libs \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring intl gd zip bcmath \
    && apk del icu-dev libpng-dev libxml2-dev libzip-dev oniguruma-dev postgresql-dev

WORKDIR /var/www/html

# Copy vendor first for layer caching
COPY --from=vendor /app/vendor /var/www/html/vendor
COPY . /var/www/html

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && composer dump-autoload --no-dev --optimize --working-dir=/var/www/html

# nginx + supervisord config
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisord.conf

EXPOSE 8080
HEALTHCHECK --interval=30s --timeout=3s --start-period=10s --retries=3 \
    CMD curl -fsS http://127.0.0.1:8080/api/health || exit 1

CMD ["supervisord", "-c", "/etc/supervisord.conf", "-n"]
