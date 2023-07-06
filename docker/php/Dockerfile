FROM php:8.2-fpm-alpine

RUN apk add libpq-dev && docker-php-ext-install pdo_pgsql

ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
