# Stage 1: Build the www container
FROM php:8.1.6-apache AS www
ENV APACHE_DOCUMENT_ROOT /var/www/html
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Stage 2: Build the db container
FROM mysql:latest AS db
COPY sql /docker-entrypoint-initdb.d

# Stage 3: Build the phpmyadmin container
FROM phpmyadmin/phpmyadmin AS phpmyadmin

# Stage 4: Create the final image
FROM alpine
COPY --from=www /var/www/html /var/www/html
COPY --from=db /docker-entrypoint-initdb.d /docker-entrypoint-initdb.d
COPY --from=phpmyadmin / /phpmyadmin