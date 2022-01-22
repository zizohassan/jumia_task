FROM php:8.1-fpm-alpine

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install pdo pdo_mysql
WORKDIR /app
COPY . .
RUN composer install

CMD php artisan serve --host=0.0.0.0 --port=9000
EXPOSE 9000
