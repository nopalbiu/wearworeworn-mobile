#!/usr/bin/env bash

composer install --no-dev --optimize-autoloader

npm install
npm run build

php artisan optimize:clear

php artisan migrate --force