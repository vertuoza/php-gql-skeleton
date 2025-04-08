# keep something similar to kevin
FROM php:8.2-fpm as base


WORKDIR /var/www/html

COPY ./src /var/www/html/src
COPY ./composer.json /var/www/html/composer.json
COPY ./index.php /var/www/html/index.php
COPY ./assets /var/www/html/assets
# Use for the local env to easiest load vendor with the IDE.
# COPY ./vendor /var/www/html/vendor


RUN ls .

# Install dependencies
RUN apt-get update \
    && apt-get install -y \
    zip \
    unzip

RUN docker-php-ext-install mysqli pdo pdo_mysql


RUN apt-get install -y \
    libmagickwand-dev --no-install-recommends
RUN pecl install imagick \
    && docker-php-ext-enable imagick

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install
RUN composer dump-autoload



################## Development image ##################
FROM base as development


RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Should be put in the mutlistage dockerfile as dev base.
COPY ./debug/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
COPY ./debug/error_reporting.ini /usr/local/etc/php/conf.d/error_reporting.ini

# Install nodemon to watch php server
RUN apt-get update \
    && apt-get install -y \
    nodejs \
    npm
RUN npm install -g nodemon


EXPOSE 9000
EXPOSE 80

# hot reloading enabled
CMD ["php", "./index.php"]



################## Production image ##################
FROM base as production

RUN rm -rf /var/www/html/tests
RUN rm -rf /var/www/html/src/**/*.spec

EXPOSE 80

CMD ["php", "./index.php"]
