FROM php:7

# get the node package
RUN curl -sL https://deb.nodesource.com/setup_6.x | bash

# install tools needed for this image
RUN apt-get update -y && apt-get upgrade -y && apt-get install -y --no-install-recommends \
openssl \
curl \
nodejs \
libmcrypt-dev && \
rm -rf /var/lib/apt/lists/*

# install composer, and the PDO and mcrypt PHP extensions
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
docker-php-ext-install pdo_mysql mcrypt


# copy the source files to the image
WORKDIR /wiki
COPY . /wiki

# run composer to install dependencies
RUN composer install

# start the development server
CMD php artisan serve --host=0.0.0.0 --port=10000

# make accessible on port 10000
EXPOSE 10000