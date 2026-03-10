#!/bin/sh

php artisan config:cache
php artisan route:cache
php artisan view:cache

php artisan schedule:work &
php artisan queue:work --daemon --tries=3 &

exec frankenphp php-server --root public/
