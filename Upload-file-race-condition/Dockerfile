FROM php:8.1-apache
RUN a2enmod rewrite
# tạo thư mục uploads với quyền rộng (demo)
RUN mkdir -p /var/www/html/uploads && chown www-data:www-data /var/www/html/uploads && chmod 0777 /var/www/html/uploads
