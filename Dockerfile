FROM php:7

RUN apt-get update -y && apt-get upgrade -y && apt-get install -y --no-install-recommends \
openssl \
git \
curl \
libmcrypt-dev && \
rm -rf /var/lib/apt/lists/*

# install composer globally, then install the mcrypt and pdo PHP extensions
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
docker-php-ext-install pdo_mysql mcrypt

# install node.js and run npm install
RUN curl -sL https://deb.nodesource.com/setup_6.x | bash && apt-get install nodejs && npm install && npm run dev

WORKDIR /wiki

COPY . /wiki

# run composer
RUN composer install

CMD php artisan serve --host=0.0.0.0 --port=10000

EXPOSE 10000