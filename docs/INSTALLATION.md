# Installation & Setup

## Installation
Install via composer:

```bash
composer require thatobabusi/laravel-ddd
```

> [!IMPORTANT]
> See [UPGRADING](../UPGRADING.md) for full details.

## Initializing
Run the install command to publish config, register domain paths, and optionally publish stubs.
```bash
php artisan ddd:install
```

## Deployment
Run `php artisan ddd:optimize` during deployment to optimize autoloading. If `php artisan optimize` is used, this is handled automatically.
