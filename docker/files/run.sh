#!/bin/sh

[ -f /run-pre.sh ] && /run-pre.sh

chown -R nginx:nginx /app

if [ ! -d /app/wordpress ] ; then
  mkdir -p /app/wordpress
fi

# php-fpm
mkdir -p /app/logs/php-fpm

# nginx
mkdir -p /app/logs/nginx
mkdir -p /tmp/nginx
chown nginx:nginx /tmp/nginx
chown -R nginx:nginx /app

php-fpm7
nginx
