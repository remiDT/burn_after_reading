FROM php:7.4-fpm

ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS="0" \
    PHP_OPCACHE_MAX_ACCELERATED_FILES="10000" \
    PHP_OPCACHE_MEMORY_CONSUMPTION="192" \
    PHP_OPCACHE_MAX_WASTED_PERCENTAGE="10"

RUN apt-get update -yqq \
    && apt-get install \
        wget \
        sudo \
        vim \
        curl \
        git \
        zip \
        unzip \
        locales \
        zlib1g-dev \
        libxml2-dev \
        libicu-dev \
        libpq-dev \
        libonig-dev \
        libzip-dev \
        -yqq

RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl mbstring xml

COPY . /var/www/bar
WORKDIR /var/www/bar

RUN curl -s https://getcomposer.org/installer | php && php composer.phar install --no-interaction;
RUN chown -R www-data:www-data /var/www/bar;

COPY ./docker-ressources/zz-bar.conf /usr/local/etc/php-fpm.d/zz-bar.conf
COPY docker-ressources/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

EXPOSE 9000
CMD ["php-fpm"]
