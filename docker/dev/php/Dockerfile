FROM php:8.1-fpm-alpine

COPY . /var/www/html

RUN curl -sS https://getcomposer.org/installer -o composer-setup.php

RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer

RUN apk --no-cache add libpq-dev

RUN docker-php-ext-install pdo pdo_pgsql

EXPOSE 9000

CMD ["php-fpm"]
