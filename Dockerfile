FROM docker.io/php:5.6-fpm-alpine

# Устанавливаем системные зависимости
RUN apk add --no-cache \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    curl-dev \
    icu-dev \
    postgresql-dev \
    git \
    unzip \
    curl \
    libmemcached-dev \
    mysql-client

RUN pecl channel-update pecl.php.net


RUN apk add --no-cache --virtual .build-deps \
        autoconf \
        gcc \
        g++ \
        make \
        libc-dev \
        pcre-dev \
    && pecl install memcache-2.2.7 \
    && docker-php-ext-enable memcache

# RUN apk add --no-cache libmemcached-dev \
    # && pecl install memcache-3.0.8 \
    # && docker-php-ext-enable memcache

# RUN apk add --no-cache libmemcached-dev \
#     && pecl install memcached-2.1.0 \
#     && docker-php-ext-enable memcached

# Устанавливаем расширения PHP
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) \
        gd \
        mysqli \
        pdo_mysql \
        curl \
        mbstring \
        xml \
        zip \
        intl \
        bcmath \
        soap

# Устанавливаем Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=1.10.26

WORKDIR /var/www

COPY vendor composer.json composer.lock  ./

# Устанавливаем зависимости
#RUN composer install --no-dev --no-scripts --no-autoloader --optimize-autoloader --ignore-platform-reqs

COPY . .

RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www \
    && chmod -R 777 /var/www/db /var/www/media /var/www/public;

# RUN composer dump-autoload --optimize --ignore-platform-reqs

CMD ["php-fpm"]

