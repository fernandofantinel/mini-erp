FROM php:8.2-fpm-bullseye

RUN apt-get update && apt-get install -y \
    curl gnupg git unzip libzip-dev \
    && docker-php-ext-install zip pdo pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html