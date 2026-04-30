FROM php:8.4-apache

RUN a2enmod rewrite

RUN apt-get update && apt-get install -y --no-install-recommends \
        libpq-dev \
        libzip-dev \
        unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_pgsql
RUN docker-php-ext-install pgsql
RUN docker-php-ext-install zip
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install opcache

RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.interned_strings_buffer=8'; \
    echo 'opcache.max_accelerated_files=10000'; \
    echo 'opcache.validate_timestamps=1'; \
    echo 'opcache.revalidate_freq=0'; \
    echo 'opcache.save_comments=1'; \
} > /usr/local/etc/php/conf.d/opcache.ini

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN printf '<VirtualHost *:80>\n\tDocumentRoot /var/www/html/public\n\t<Directory /var/www/html/public>\n\t\tAllowOverride All\n\t\tRequire all granted\n\t</Directory>\n</VirtualHost>\n' \
    > /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html
