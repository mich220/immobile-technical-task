FROM php:8.2.0-fpm-alpine3.16

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apk add --no-cache bash vim git

RUN apk add --no-cache zip \
    libzip-dev \
    zip \
    libcurl curl curl-dev \
    autoconf gcc g++ make

RUN apk add --update linux-headers

RUN \
    docker-php-ext-install zip && \
    docker-php-ext-install pdo && \
    docker-php-ext-install pdo_mysql

RUN \
    pecl install apcu && \
    pecl install redis && \
    pecl install xdebug

RUN \
    docker-php-ext-enable apcu && \
    docker-php-ext-enable redis && \
    docker-php-ext-enable xdebug

EXPOSE 9000

STOPSIGNAL SIGQUIT
