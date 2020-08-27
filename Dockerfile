FROM php:7.3-apache-buster

RUN apt update && apt install -y libcurl4-openssl-dev pkg-config libssl-dev zip unzip

RUN apt install -y libpq-dev
RUN docker-php-ext-install -j$(nproc) pgsql pdo_pgsql \
    && docker-php-ext-enable pgsql pdo_pgsql 

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# get npm
COPY --from=node:10.21.0-buster-slim /usr/local /usr/local

ENV APACHE_DOCUMENT_ROOT /var/www/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/

COPY . /var/www/

RUN rm -rf /var/www/storage/sessions/* /var/www/storage/views/* /var/www/storage/logs/* 
RUN chown -R www-data: /var/www/storage

COPY docker/apache/ /etc/apache2/sites-available/

RUN composer install
RUN npm install

RUN chmod +x docker/startup.sh

CMD ["/var/www/docker/startup.sh"]