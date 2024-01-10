#!/bin/sh

# [rachow] - This deployment script to be run on prod.

# turn on the maintenance
php artisan down

# pull the latest changes from GIT repo
git reset --hard
git clean -df
git pull origin master

# install/update Dependencies
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# run the database migration or skip should we, prod = careful
php artisan migrate --force

# clear cache
php artisan cache:clear

# clear expired password reset tokens
php artisan auth:clear-resets

# clear and cache the routes
php artisan route:clear
php artisan route:cache

# clear and then cache the configs
php artisan config:clear
php artisan config:cache

# clear the blade view cache
php artisan view:clear

# cleaning up SPL autoloader
composer dump-autoload

# install all the node modules
npm install

# perform build process, SASS/LESS and laravel-mix
npm run prod

# brin the app back online
php artisan up

