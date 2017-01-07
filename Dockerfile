FROM php:5.6.29-apache

RUN apt-get update \
  && apt-get install -y git libssl-dev zlib1g-dev libicu-dev g++ \
  && apt-get install -y libpq-dev \
  && docker-php-ext-install intl mbstring zip pdo pdo_pgsql \
  && a2enmod rewrite

COPY apache2.conf /etc/apache2/apache2.conf

RUN curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/bin/ --filename=composer

WORKDIR /var/www/html

COPY composer.json ./
COPY composer.lock ./

RUN composer install --no-scripts --no-autoloader

COPY . ./

RUN rm -rf ./app/cache/prod \
    && rm -rf ./app/cache/dev

RUN composer dump-autoload --optimize --no-dev --classmap-authoritative

USER root

RUN chmod -R 0777 ./app/cache
RUN chmod -R 0777 ./app/logs