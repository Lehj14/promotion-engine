FROM php:8.0.0-fpm

RUN apt-get update && apt-get install -y git
RUN docker-php-ext-install pdo_mysql

RUN apt-get install -y \
        libzip-dev \
        zip \
  && docker-php-ext-install zip

COPY --from=composer:2.3.4 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www