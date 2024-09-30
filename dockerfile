FROM php:8.2

RUN apt-get update -y && apt-get install -y \
    openssl \
    zip \
    unzip \
    git \
    libonig-dev \
    libzip-dev \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN pecl install redis \
    && docker-php-ext-enable redis

WORKDIR /app

COPY . /app

RUN composer install

RUN chmod -R 777 /app/storage /app/bootstrap/cache

RUN php artisan storage:link

CMD php artisan serve --host=0.0.0.0 --port=8181

EXPOSE 8181
