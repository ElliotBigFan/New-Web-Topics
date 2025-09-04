FROM php:7.4-apache

# Cài zip extension
RUN apt-get update && apt-get install -y libzip-dev \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

COPY php.ini /usr/local/etc/php/
# Copy source vào container
COPY . /var/www/html/

# Tạo thư mục upload và cấp quyền ghi
RUN chown -R www-data:www-data /var/www/html/uploads
