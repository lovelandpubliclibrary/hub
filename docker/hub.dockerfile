FROM alpine

WORKDIR /hub

RUN apk add --no-cache --update --virtual .webserver \
		php7 \
		php7-fpm \
		php7-pdo_mysql \
		php7-apache2 \
		php7-json \
		php7-phar \
		php7-iconv \
		apache2 \
	&& rm -rf /var/lib/apk/* \
	&& wget -qO - https://getcomposer.org/installer | php \
	&& mv composer.phar /usr/local/bin/composer

EXPOSE 10000