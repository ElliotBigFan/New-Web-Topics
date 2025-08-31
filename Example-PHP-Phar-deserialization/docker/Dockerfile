FROM php:7.2-apache

RUN a2enmod rewrite
RUN echo "phar.readonly=0" > /usr/local/etc/php/conf.d/phar.ini
RUN echo "phar.require_hash=0" >> /usr/local/etc/php/conf.d/phar.ini

WORKDIR /var/www/html
