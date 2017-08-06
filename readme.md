# Install

composer install

Add "z5internet\ReactUserFramework\ReactUserFrameworkServiceProvider::class" as a service provider in ./config/app.php

php artisan react-user-framework:install

php artisan vendor:publish

php artisan migrate

# Build

php artisan react-user-framework:build

# Develop

php artisan db:seed

php artisan react-user-framework:server

./resources/assets/react-app/* => customise react-user-framework

./config/react-user-framework => config for react-user-framework

