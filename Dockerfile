# syntax=docker/dockerfile:1.6

ARG NODE_VERSION=20
ARG PHP_VERSION=8.2

FROM composer:2 AS composer-bin

FROM php:${PHP_VERSION}-cli-bullseye AS composer
COPY --from=composer-bin /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
ARG COMPOSER_INSTALL_FLAGS="--no-dev --no-interaction --prefer-dist --optimize-autoloader"
RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip libssl-dev pkg-config \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && rm -rf /var/lib/apt/lists/*
COPY composer.json composer.lock* ./
RUN composer install ${COMPOSER_INSTALL_FLAGS}

FROM node:${NODE_VERSION} AS frontend
WORKDIR /app
COPY package*.json ./
RUN if [ -f package-lock.json ]; then npm ci --include=dev; else npm install; fi
COPY resources ./resources
COPY vite.config.js postcss.config.js tailwind.config.js ./
COPY app ./app
COPY public ./public
RUN npm run build

FROM php:${PHP_VERSION}-fpm-bullseye AS runtime

ENV PORT=8080 \
    APP_ENV=production \
    APP_DEBUG=false

RUN apt-get update && apt-get install -y --no-install-recommends \
    nginx \
    supervisor \
    gettext-base \
    git \
    unzip \
    curl \
    libpq-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    libssl-dev \
    pkg-config \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" gd zip intl pdo_mysql pdo_pgsql bcmath \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY --from=composer /var/www/html/vendor ./vendor
COPY --from=frontend /app/public/build ./public/build
COPY . .

RUN chown -R www-data:www-data storage bootstrap/cache \
    && find storage bootstrap/cache -type d -exec chmod 775 {} \; \
    && find storage bootstrap/cache -type f -exec chmod 664 {} \;

COPY docker/nginx.conf.template /etc/nginx/conf.d/default.conf.template
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 8080
HEALTHCHECK --interval=30s --timeout=5s --retries=3 CMD curl -f http://127.0.0.1:${PORT}/ || exit 1

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
