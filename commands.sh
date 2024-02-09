#!/bin/bash

echo "Run composer, migrations and node builder"

export NVM_DIR=$HOME/.nvm;
source $NVM_DIR/nvm.sh;

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
nvm use --delete-prefix v16.20.2
cd resources/admin
npm i
npm run prod
cd ../..

## Install site resource
npm i
npm run prod
