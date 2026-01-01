FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libpq-dev \
    pdo_pgsql pgsql \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim unzip git curl libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_pgsql pgsql pdo_mysql mbstring exif pcntl bcmath gd zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
