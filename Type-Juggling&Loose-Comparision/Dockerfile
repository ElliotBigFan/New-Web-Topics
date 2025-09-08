FROM php:7.4-apache

# Copy source
COPY src/ /var/www/html/

# Enable display errors for lab debugging
RUN echo "display_errors=On" > /usr/local/etc/php/conf.d/display_errors.ini
