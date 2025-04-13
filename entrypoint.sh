#!/bin/bash


if [ ! -f .env ]; then
  cp .env.example .env
fi

composer install --no-dev --optimize-autoloader --no-scripts
php artisan package:discover --ansi
php artisan key:generate


php artisan migrate --force
php artisan queue:table
php artisan migrate --force

exec "$@"
