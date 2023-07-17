FROM php:8.2-fpm-alpine

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_MEMORY_LIMIT=-1
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apk add --no-cache libpq-dev autoconf build-base linux-headers && \
    docker-php-ext-install pdo_pgsql && \
    docker-php-ext-install sockets && \
    pecl install redis && docker-php-ext-enable redis

# last version on git
# https://github.com/phpredis/phpredis/
# RUN apk add git autoconf build-base && \
#    git clone https://github.com/phpredis/phpredis.git && cd phpredis && \
#    phpize && ./configure && make && make install && \
#    docker-php-ext-enable redis

RUN adduser -D appuser wheel
USER appuser

WORKDIR /var/www/html/
