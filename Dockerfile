FROM php:8.2-fpm


RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    libzip-dev \
    libpq-dev \
    supervisor \
    nano

RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


WORKDIR /var/www


COPY . .


RUN composer install --no-dev --optimize-autoloader


COPY ./supervisord.conf /etc/supervisord.conf


EXPOSE 8000


CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
