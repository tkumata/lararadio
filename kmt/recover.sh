#!/bin/sh
#
cd ~/lararadio
php artisan clear-compiled
composer dump-autoload -o
php artisan optimize
php artisan cache:clear
php artisan config:clear
php artisan migrate:refresh --seed
