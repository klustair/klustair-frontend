#!/bin/sh
mkdir -pv storage/app/public
mkdir -pv storage/debugbar
mkdir -pv storage/framework/cache
mkdir -pv storage/framework/sessions
mkdir -pv storage/framework/testing
mkdir -pv storage/framework/views
mkdir -pv storage/logs/
chown -R www-data:www-data storage/app
chown -R www-data:www-data storage/debugbar
chown -R www-data:www-data storage/framework
chown -R www-data:www-data storage/logs

rm -v storage/framework/views/*.php