
FROM php:8.3-fpm-bookworm

# Dépendances système et extensions PHP
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libicu-dev \
    libpq-dev \
    libonig-dev \
    && docker-php-ext-install -j"$(nproc)" \
        intl \
        mbstring \
        pdo_pgsql \
        opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Composer 2.10.3
COPY --from=composer:2.10.3 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Installation des dépendances depuis le lockfile
COPY composer.json composer.lock symfony.lock ./

RUN COMPOSER_ALLOW_SUPERUSER=1 composer install \
    --no-interaction \
    --prefer-dist \
    --no-progress \
    --no-scripts

# Préparation des volumes persistants
RUN mkdir -p var config/jwt \
    && chown -R www-data:www-data var config/jwt

# Configuration PHP de développement
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini" \
    && echo "memory_limit=512M" > "$PHP_INI_DIR/conf.d/workshop.ini" \
    && echo "opcache.enable=1" >> "$PHP_INI_DIR/conf.d/workshop.ini" \
    && echo "opcache.validate_timestamps=1" >> "$PHP_INI_DIR/conf.d/workshop.ini"

EXPOSE 9000

CMD ["php-fpm"]
