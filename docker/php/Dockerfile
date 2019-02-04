FROM php:7.2-fpm-alpine

RUN set -xe && \
	apk update && \
	apk upgrade --no-cache && \
    \
    # install necessary packages
    docker-php-ext-install pdo_mysql

WORKDIR /hub/


# # install dependencies
# RUN apt-get update -y && apt-get upgrade -y && apt-get install -y --no-install-recommends \
# openssl \
# curl \
# nodejs \
# libmcrypt-dev \
# zip \
# unzip \
# && docker-php-ext-install pdo_mysql \
# && pecl install mcrypt-1.0.1 \
# && pecl install xdebug-2.6.1 \
# && docker-php-ext-enable mcrypt \
# && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
# && rm -rf /var/lib/apt/lists/*

# # start the web server
# CMD php artisan serve --host=0.0.0.0 --port=10000

# # make container accessible on port 10000
# EXPOSE 10000