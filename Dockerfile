FROM php:7

RUN \
apt-get update -y && apt-get upgrade -y && apt-get install -y --no-install-recommends \
openssl \
git \
curl \
libmcrypt-dev && \
rm -rf /var/lib/apt/lists/*

RUN \
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
docker-php-ext-install pdo_mysql mcrypt

WORKDIR /app

COPY . /app

RUN composer install

CMD php artisan serve --host=0.0.0.0 --port=10000

EXPOSE 10000