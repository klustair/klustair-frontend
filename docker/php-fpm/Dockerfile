FROM php:fpm-alpine

RUN apk --no-cache add postgresql-dev

RUN docker-php-ext-install -j$(nproc) pgsql pdo_pgsql \
    && docker-php-ext-enable pgsql pdo_pgsql 

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# get npm
COPY --from=node:10.23-buster-slim /usr/local /usr/local


CMD ["php-fpm"]
EXPOSE 9000