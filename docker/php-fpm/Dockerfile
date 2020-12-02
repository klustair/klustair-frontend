FROM php:7.4-fpm-alpine

RUN apk --no-cache add postgresql-dev

RUN docker-php-ext-install -j$(nproc) pgsql pdo_pgsql \
    && docker-php-ext-enable pgsql pdo_pgsql 

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www/

WORKDIR /var/www/

RUN composer install

CMD ["php-fpm"]
EXPOSE 9000
