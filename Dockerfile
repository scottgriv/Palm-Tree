# Update the php image to support mysqli
FROM php:8.1.6-apache
ENV APACHE_DOCUMENT_ROOT /var/www/html
RUN docker-php-ext-install pdo pdo_mysql mysqli