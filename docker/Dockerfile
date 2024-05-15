ARG PHP_VERSION='${PHP_VERSION:-8.1}'
ARG AP='-apache'
ARG PHP_IMAGE=${PHP_VERSION}${AP}
FROM php:${PHP_IMAGE}

LABEL maintainer="Douglas S. Santos"

ARG uid

ENV APP_DIR /var/www/html

RUN mkdir -p $APP_DIR

WORKDIR /var/www/html

ENV DEBIAN_FRONTEND noninteractive
ENV TZ=UTC

EXPOSE 80

VOLUME $APP_DIR

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update \
    && apt-get install -y git zip curl sudo unzip libicu-dev libbz2-dev libpng-dev libjpeg-dev libmcrypt-dev \
    libreadline-dev libfreetype6-dev g++ libsodium-dev libzip-dev libonig-dev libpq-dev net-tools nano \
    supervisor libcap2-bin dnsutils librsvg2-bin fswatch libc-client-dev libkrb5-dev libxml2-dev

RUN apt-get install -y software-properties-common nmap wget sudo net-tools nano libmagickwand-dev
RUN pecl install redis imagick

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql
RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl

RUN docker-php-ext-install bz2 intl sockets iconv bcmath opcache calendar mbstring pdo pdo_mysql pdo_pgsql mysqli pgsql gd sodium zip

RUN docker-php-ext-enable opcache redis imagick

COPY ./php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.5/install.sh | bash

ARG NODE_VERSION
ENV NVM_DIR=/root/.nvm
RUN . "$NVM_DIR/nvm.sh" && nvm install $NODE_VERSION
RUN . "$NVM_DIR/nvm.sh" && nvm use v$NODE_VERSION
RUN . "$NVM_DIR/nvm.sh" && nvm alias default v$NODE_VERSION
ENV PATH="$NVM_DIR/versions/node/v$NODE_VERSION/bin/:${PATH}"
RUN node --version
RUN npm --version

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

ARG PHP_VERSION

COPY ./php/php.ini /etc/php/$PHP_VERSION/cli/conf.d/custom-php.ini

RUN a2enmod headers && a2enmod rewrite && a2enmod expires
