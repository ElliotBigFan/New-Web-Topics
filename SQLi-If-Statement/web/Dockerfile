FROM php:8.0-apache

RUN docker-php-ext-install mysqli

# Bật hiển thị lỗi để debug dễ
RUN echo "display_errors=On\nerror_reporting=E_ALL" > /usr/local/etc/php/conf.d/errors.ini
