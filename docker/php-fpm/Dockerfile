FROM php:8-fpm-alpine

RUN apk --no-cache add postgresql-dev libzip-dev libxml2-dev 

# LDAP related packages
RUN apk --no-cache add ldb-dev libldap openldap-dev

RUN docker-php-ext-install -j$(nproc) pgsql pdo_pgsql xml zip ldap
RUN docker-php-ext-enable pgsql pdo_pgsql xml zip ldap


# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN chown -R www-data:www-data /var/www

USER www-data
COPY --chown=www-data:www-data . /var/www/

WORKDIR /var/www/

RUN composer install

CMD ["php-fpm"]
EXPOSE 9000
