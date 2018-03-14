FROM alpine:3.6

RUN echo 'http://dl-4.alpinelinux.org/alpine/edge/testing' >> /etc/apk/repositories \
    && apk update \
    && apk add bash nginx ca-certificates \
    php7-fpm \
    php7-json \
    php7-zlib \
    php7-xml \
    php7-pdo \
    php7-phar \
    php7-openssl \
    php7-pdo_mysql \
    php7-mysqli \
    php7-session \
    php7-gd \
    php7-iconv \
    php7-mcrypt \
    php7-curl \
    php7-opcache \
    php7-ctype \
    php7-apcu \
    php7-intl \
    php7-bcmath \
    php7-mbstring \
    php7-dom \
    php7-xmlreader \
    php7-simplexml \
    mysql-client \
    curl \
    musl \
    msmtp \
    dumb-init \
    && apk --update --no-cache add tar
RUN rm -rf /var/cache/apk/*

ADD files/nginx.conf /etc/nginx/
ADD files/php-fpm.conf /etc/php7/
ADD files/msmtprc /etc/msmtprc
ADD files/run.sh /
RUN chmod +x /run.sh
RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar && chmod +x wp-cli.phar && mv wp-cli.phar /usr/bin/wp-cli && chown nginx:nginx /usr/bin/wp-cli

EXPOSE 80
VOLUME ["/app"]

ENTRYPOINT ["/usr/bin/dumb-init", "--"]
CMD ["bash", "-c", "exec /run.sh"]
