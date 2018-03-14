#!/bin/sh

chown -R nginx:nginx /app

if [ ! -d /app/wordpress ] ; then
  mkdir -p /app/wordpress
fi

# php-fpm
mkdir -p /app/logs/php-fpm

# session storage
if [ ! -d /app/sessions ] ; then
  mkdir -p /app/sessions
  chown nginx:nginx /app/sessions
  chmod 600 /app/sessions
fi

# mail sending
cat >> /etc/msmtprc << EOT
maildomain $MAILDOMAIN
host $SMTP_HOST
user $SMTP_USER
password $SMTP_PASS
EOT

# nginx
mkdir -p /app/logs/nginx
mkdir -p /tmp/nginx
chown nginx:nginx /tmp/nginx
chown -R nginx:nginx /app

php-fpm7
nginx
