FROM php:7.3-apache-buster

RUN apt update && apt install -y libmongoc-1.0-0 libcurl4-openssl-dev pkg-config libssl-dev
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

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