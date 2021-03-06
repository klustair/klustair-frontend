#!/bin/bash

mkdir -p ${APACHE_DOCUMENT_ROOT:-/var/www/public/}/../storage/app/public
mkdir -p ${APACHE_DOCUMENT_ROOT:-/var/www/public/}/../storage/framework/cache
mkdir -p ${APACHE_DOCUMENT_ROOT:-/var/www/public/}/../storage/framework/sessions
mkdir -p ${APACHE_DOCUMENT_ROOT:-/var/www/public/}/../storage/framework/testing
mkdir -p ${APACHE_DOCUMENT_ROOT:-/var/www/public/}/../storage/framework/views
mkdir -p ${APACHE_DOCUMENT_ROOT:-/var/www/public/}/../storage/logs

chown -R www-data:www-data ${APACHE_DOCUMENT_ROOT:-/var/www/public/}/../storage

cat >>/etc/apache2/envvars <<EOL
export APACHE_DOCUMENT_ROOT=${APACHE_DOCUMENT_ROOT:-/var/www/public/}
export APACHE_SERVERNAME=${APACHE_SERVERNAME:-klustair.test}

export APP_NAME=${APP_NAME:-Klustair}
export APP_ENV=${APP_ENV:-Klustair}local
export APP_KEY=${APP_KEY}
export APP_DEBUG=${APP_DEBUG:-true}
export APP_URL=${APP_URL}

export LOG_CHANNEL=${LOG_CHANNEL:-stack}

export DB_CONNECTION=${DB_CONNECTION:-pgsql}
export DB_HOST=${DB_HOST}
export DB_PORT=${DB_PORT:-5432}
export DB_DATABASE=${DB_DATABASE:-klustair}
export DB_USERNAME=${DB_USERNAME:-root}
export DB_PASSWORD='${DB_PASSWORD}'
export ANCHORE_ENABLED='${ANCHORE_ENABLED:-false}'
export ANCHORE_API_URL='${ANCHORE_API_URL}'
export ANCHORE_CLI_USER='${ANCHORE_CLI_USER:-admin}'
export ANCHORE_CLI_PASS='${ANCHORE_CLI_PASS}'

export BROADCAST_DRIVER=${BROADCAST_DRIVER:-log}
export CACHE_DRIVER=${CACHE_DRIVER:-file}
export QUEUE_CONNECTION=${QUEUE_CONNECTION:-sync}
export SESSION_DRIVER=${SESSION_DRIVER:-file}
export SESSION_LIFETIME=${SESSION_LIFETIME:-120}
EOL

apache2-foreground