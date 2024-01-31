# Laravel Products API

## Table of content

- [Overview](#overview)
- [Installation](#installation)
- [Database connection](#database-connection)
- [Available routes](#available-routes)
- [Running tests](#running-tests)

## Overview

API built with Laravel to get a list of products with their discounts applied. You can filter this list by category and by "price less than".

## Installation

- Download the project
- Run the following command in the project directory:
```shell
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```
- Initiate Sail:
```shell
./vendor/bin/sail up
```
- Run migrations and seeders to populate the database
```shell
./vendor/bin/sail php artisan migrate --seed
```
- (Optional) Run this command to execute the "stress seeder" to populate the database with 20.000 products in order to check the app performance
```shell
./vendor/bin/sail artisan db:seed --class=StressSeeder
```
- You can access to the website using this URL in your browser: http://localhost

## Database connection

- Server host: localhost
- Database name: laravel_products_api
- User name: sail
- Password: password

## Available routes

- Get products
```shell
localhost/api/products
```

- Filter by category
```shell
localhost/api/products?category=boots
```

- Filter by price:
```shell
localhost/api/products?priceLessThan=90000
```

## Running tests

- Use this command to run all the tests
```shell
./vendor/bin/sail test
```
