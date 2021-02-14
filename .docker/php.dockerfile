FROM php:7.4-fpm-alpine

ADD ./configs/php/www.conf /usr/local/etc/php-fpm.d/www.conf

RUN addgroup -g 1000 laravel && adduser -G laravel -g laravel -s /bin/sh -D laravel

RUN mkdir -p /var/www/html

RUN chown laravel:laravel /var/www/html

WORKDIR /var/www/html

Run apk add --no-cache postgresql-dev
RUN docker-php-ext-install pdo_pgsql
