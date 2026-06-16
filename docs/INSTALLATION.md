# Installation & Setup

## Installation
Install via composer:

```bash
composer require tey/laravel-ddd
```

> [!IMPORTANT]
> **Upgrading from `lunarstorm/laravel-ddd` v2?** First update to v2.1.2, then run `ddd:upgrade`:
> ```bash
> composer require lunarstorm/laravel-ddd:"^2.1.2"
> php artisan ddd:upgrade
> ```
> See [UPGRADING](../UPGRADING.md) for full details.

## Initializing
Run the install command to publish config, register domain paths, and optionally publish stubs.
```bash
php artisan ddd:install
```

## Deployment
Run `php artisan ddd:optimize` during deployment to optimize autoloading. If `php artisan optimize` is used, this is handled automatically.
