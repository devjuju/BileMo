FROM php:8.3-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        build-essential \
        curl \
        zlib1g-dev \
        g++ \
        libicu-dev \
        zip \
        libzip-dev \
        libpng-dev \
        libjpeg-dev \
        libwebp-dev \
        libfreetype6-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install mysqli pdo pdo_mysql intl opcache zip

RUN pecl install apcu \
    && docker-php-ext-enable apcu

RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install gd exif

RUN a2enmod rewrite headers

WORKDIR /var/www

RUN curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

RUN chown -R www-data:www-data /var/www

