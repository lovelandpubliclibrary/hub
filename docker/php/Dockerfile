FROM php:7.2-fpm-alpine

# install necessary packages
RUN set -xe && \
    docker-php-ext-install pdo_mysql

WORKDIR /hub/