FROM php:7

MAINTAINER "Kevin Briggs, redisforlosers@gmail.com"

# copy the source files to the image
WORKDIR /hub
COPY . /hub

# copy the php configuration to the image
COPY ./docker/php.ini /usr/local/etc/php/php.ini

# get the node package so we can run 'apt-get install nodejs'
RUN apt-get update -y && apt-get install -y --no-install-recommends gnupg
RUN curl -sL https://deb.nodesource.com/setup_6.x | bash

# install dependencies
RUN apt-get update -y && apt-get upgrade -y && apt-get install -y --no-install-recommends \
openssl \
curl \
nodejs \
libmcrypt-dev \
zip \
unzip \
&& rm -rf /var/lib/apt/lists/* \
&& docker-php-ext-install pdo_mysql \
&& pecl install mcrypt-1.0.1 \
&& docker-php-ext-enable mcrypt \
&& curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
&& composer install --no-interaction

# start the web server
CMD php artisan serve --host=0.0.0.0 --port=10000

# make container accessible on port 10000
EXPOSE 10000