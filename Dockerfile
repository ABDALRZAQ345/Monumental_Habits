FROM php:8.2-fpm


RUN apt-get update && apt-get install -y \
    git curl libzip-dev unzip libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

COPY . /var/www
WORKDIR /var/www


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh


ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

CMD ["php-fpm"]
