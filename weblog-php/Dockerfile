FROM php:8.2-apache

# Installer mysqli et pdo_mysql
RUN docker-php-ext-install mysqli pdo pdo_mysql
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Active mod_rewrite si besoin
RUN a2enmod rewrite
