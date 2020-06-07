FROM php:7.1-fpm
LABEL Name=jeudeloie Version=0.0.1 Maintainer="Claire Serra"

RUN apt-get -y update && apt-get -y install \
    libpng-dev \
    libjpeg-dev \
    libpq-dev \
    libicu-dev \
    libmcrypt-dev \
    libmcrypt-dev \
    zip \
    mcrypt \
    git \
    default-mysql-client \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /var/www/html
WORKDIR /var/www/html
RUN COMPOSER_MEMORY_LIMIT=2G composer update --prefer-source --no-interaction \
    && php bin/console cache:clear --env=prod
