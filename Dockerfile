FROM composer:2.6.6 AS vendor

WORKDIR /apps

COPY ./composer.json ./composer.json
COPY ./composer.lock ./composer.lock

RUN composer install --no-interaction --no-scripts --prefer-dist


FROM php:8.2-fpm-alpine3.18 AS system

RUN apk update && apk add --no-cache \
    postgresql-dev \
    libpq \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    zlib-dev \
    libzip-dev \
    oniguruma-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo_pgsql pgsql gd zip exif pcntl

ARG UID=1000
ARG GID=1000

USER root:root
RUN addgroup -g ${GID} -S apps && \
    adduser -u ${UID} -S -D -G apps apps -h /home/apps


FROM system AS release
LABEL org.opencontainers.image.authors="tomasz.kisiel@example.com"

USER apps:apps
WORKDIR /apps

COPY --chown=apps:apps --from=vendor /apps/vendor ./vendor
COPY --chown=apps:apps ./ ./

RUN php artisan package:discover --ansi && \
    php artisan storage:link

CMD ["php-fpm"]
