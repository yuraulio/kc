#!/bin/bash

echo "Run composer, migrations and node builder"

## just in case
cd $PWD

## print out versions of everything we need
echo "--------------"
php -v
echo "--------------"
node -v
echo "--------------"
nvm -v
echo "--------------"

## install packages
composer install --ignore-platform-reqs
composer dump-autoload

## Run all migration
php artisan migrate
php artisan migrate --path=/database/migrations/cms
php artisan migrate --path=/database/migrations/cms-plugins

## clear basic stuff
php artisan view:clear
php artisan config:clear
php artisan cache:clear

## Install new admin resource needs to be done before build in root
nvm use 15
cd resources/admin
npm -i
npm run dev

cd ../..
## TODO Temp solution we need to switch on 15 at least. Issue with python3 on prod
nvm use 14
## install packages in root folder
npm install --unsafe-perm=true --allow-root
npm run dev
