FROM php:7

RUN curl -sL https://deb.nodesource.com/setup_6.x | bash

RUN apt-get update -y && apt-get upgrade -y && apt-get install -y --no-install-recommends \
openssl \
curl \
nodejs \
libmcrypt-dev && \
rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
docker-php-ext-install pdo_mysql mcrypt

WORKDIR /wiki

COPY . /wiki

RUN composer install

CMD php artisan serve --host=0.0.0.0 --port=10000

EXPOSE 10000