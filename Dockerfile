FROM php:8.3-cli

RUN apt-get update && apt-get install -y git unzip libzip-dev && \
    docker-php-ext-install zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app