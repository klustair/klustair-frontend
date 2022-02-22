FROM php:8.0-apache

RUN apt update && apt install -y libcurl4-openssl-dev pkg-config libssl-dev zip unzip

RUN apt install -y libpq-dev libzip-dev libxml2-dev libldap2-dev

RUN docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/
RUN docker-php-ext-install -j$(nproc) pgsql pdo_pgsql xml zip ldap
RUN docker-php-ext-enable pgsql pdo_pgsql xml zip ldap


# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# get npm
COPY --from=node:10.23-buster-slim /usr/local /usr/local

ENV APACHE_DOCUMENT_ROOT /var/www/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite
RUN a2enmod ssl

COPY ./docker/apache/config/etc/ssl/* /etc/ssl/

EXPOSE 80
EXPOSE 443

# Set working directory
WORKDIR /var/www/

COPY . /var/www/

RUN rm -rf /var/www/storage/sessions/* /var/www/storage/views/* /var/www/storage/logs/* 
RUN chown -R www-data: /var/www/storage

COPY docker/apache/config/etc/apache2/sites-available/* /etc/apache2/sites-available/
COPY docker/apache/startup.sh /startup.sh

#RUN composer install
#RUN npm install

RUN chmod +x /startup.sh

CMD ["/startup.sh"]