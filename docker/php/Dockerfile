FROM php:7.2-fpm-alpine

WORKDIR /hub/

# install necessary packages
RUN set -xe && \
    docker-php-ext-install pdo_mysql && \
    chown -R www-data:www-data /hub/