
FROM php:8.3-fpm-bookworm

# Dependências necessárias para compilar extensões PHP.
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_pgsql \
        pcntl \
        bcmath \
        opcache \
        zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Composer oficial.
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html

# Copia primeiro os manifests para aproveitar o cache do Docker.
COPY composer.json composer.lock ./

# Dependências de produção.
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --no-progress \
    --optimize-autoloader \
    --no-scripts

# Copia o código da aplicação.
COPY --chown=www-data:www-data . .

# Gera os manifests dos pacotes instalados na imagem.
RUN mkdir -p storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && php artisan package:discover --ansi \
    && chown -R www-data:www-data storage bootstrap/cache

USER www-data

EXPOSE 9000

CMD ["php-fpm"]
