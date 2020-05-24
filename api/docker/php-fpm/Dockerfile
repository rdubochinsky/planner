FROM php:7.4-fpm-alpine

RUN apk add --update --no-cache icu-dev \
    && docker-php-ext-install intl \
    && docker-php-ext-install pdo_mysql \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && rm -rf /var/cache/apk/*

ARG APP_UID=1000
ARG APP_GID=1000

RUN addgroup -S -g $APP_UID www-app && \
    adduser -S -G www-app -u $APP_GID www-app

WORKDIR /var/www

EXPOSE 9000