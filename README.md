# DHAGRAFIS-API

How to install this project:

## Clonning from repo

## Setup Project

Download dependencies

```bash
composer install
```

Copy .env-example

> Setting your environment

```bash
cp .env-example .env
```

Generate app key

```bash
php artisan key:generate
```

Migrate database and seeder the database

```bash
php artisan migrate --seed
```

## Run program

```bash
php artisan serve
```
