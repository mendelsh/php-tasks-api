FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    sqlite sqlite-dev \
    autoconf g++ make pkgconf \
 && docker-php-ext-install pdo pdo_sqlite

WORKDIR /var/www/html

COPY composer.json composer.lock* /var/www/html/

COPY src /var/www/html
