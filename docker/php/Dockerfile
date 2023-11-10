FROM php:8.2-fpm-alpine

ARG TIMEZONE

RUN apk add --no-cache git libpq-dev autoconf build-base linux-headers libzip-dev && \
    docker-php-ext-install zip && \
    docker-php-ext-install pdo_pgsql && \
    docker-php-ext-install sockets && \
    pecl install redis && docker-php-ext-enable redis

# last version php redis extension on git
# RUN git clone https://github.com/phpredis/phpredis.git && cd phpredis && \
#     phpize && ./configure && make && make install && \
#     docker-php-ext-enable redis

RUN echo date.timezone = ${TIMEZONE} > /usr/local/etc/php/conf.d/custom.timezone.ini

RUN adduser -D appuser wheel
USER appuser
WORKDIR /var/www/html/

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY --chown=appuser:appuser ./docker/php/composer/*.pub /home/appuser/.composer/

USER root
RUN composer self-update
