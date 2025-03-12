FROM php:8.3-fpm-alpine

ADD php/www.conf /usr/local/etc/php-fpm.d/www.conf

RUN addgroup -g 1000 laravel && adduser -G laravel -g laravel -s /bin/sh -D laravel

RUN mkdir -p /var/www/html

ADD src /var/www/html

RUN apk add --no-cache \
    icu-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libzip-dev \
    oniguruma-dev \
    autoconf \
    gcc \
    g++ \
    make \
    linux-headers \
    libtool \
    git \
    lftp \
    openssh \
    sshpass \
    lftp
RUN docker-php-ext-install pdo pdo_mysql intl gd zip exif
RUN pecl install redis \
    && docker-php-ext-enable redis

RUN chown -R laravel:laravel /var/www/html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
