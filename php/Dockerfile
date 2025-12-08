FROM php:8.2-fpm

RUN docker-php-ext-install pdo_mysql

RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip 

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

