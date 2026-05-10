FROM php:8.4-fpm-alpine

ARG UID=1000
ARG GID=1000

RUN apk add --no-cache \
        bash \
        git \
        curl \
        unzip \
        libzip-dev \
        oniguruma-dev \
        icu-dev \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        $PHPIZE_DEPS \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        mbstring \
        zip \
        bcmath \
        intl \
        gd \
        opcache \
        pcntl \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del $PHPIZE_DEPS \
    && rm -rf /var/cache/apk/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN addgroup -g ${GID} -S app \
    && adduser -u ${UID} -S app -G app \
    && mkdir -p /var/www/html \
    && chown -R app:app /var/www/html

WORKDIR /var/www/html

USER app

EXPOSE 9000
CMD ["php-fpm"]
