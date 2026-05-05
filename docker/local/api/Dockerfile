FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    curl \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql \
    && sed -i 's|^;listen = 127.0.0.1:9000|listen = 9000|' /usr/local/etc/php-fpm.d/www.conf

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

CMD ["sh", "-lc", "mkdir -p /tmp/passport-keys && if [ -f /var/www/storage/oauth-private.key ] && [ -f /var/www/storage/oauth-public.key ]; then cp /var/www/storage/oauth-private.key /tmp/passport-keys/oauth-private.key && cp /var/www/storage/oauth-public.key /tmp/passport-keys/oauth-public.key && chown www-data:www-data /tmp/passport-keys/oauth-private.key /tmp/passport-keys/oauth-public.key && chmod 600 /tmp/passport-keys/oauth-private.key /tmp/passport-keys/oauth-public.key; fi; php-fpm -F"]
