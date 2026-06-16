# Domain Driven Design Toolkit for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/tey/laravel-ddd.svg?style=flat-square)](https://packagist.org/packages/tey/laravel-ddd)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jaspertey/laravel-ddd/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/jaspertey/laravel-ddd/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jaspertey/laravel-ddd/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/jaspertey/laravel-ddd/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/tey/laravel-ddd.svg?style=flat-square)](https://packagist.org/packages/tey/laravel-ddd)

Laravel-DDD is a toolkit to support domain-driven design (DDD) in Laravel applications. One of the pain points when adopting DDD is the inability to use Laravel's native `make` commands to generate objects outside the `App\*` namespace. This package aims to fill the gaps by providing equivalent commands such as `ddd:model`, `ddd:dto`, `ddd:view-model` and many more.

## Quick Start

```bash
composer require tey/laravel-ddd
php artisan ddd:install
php artisan ddd:config wizard
```

## Documentation

Complete documentation is organized into focused guides:

### Getting Started
- [Installation & Setup](docs/INSTALLATION.md) — Install, initialize, and deploy.
- [Configuration](docs/CONFIGURATION.md) — Configure domains, layers, and namespaces.

### Using the Toolkit
- [Available Commands](docs/COMMANDS.md) — Reference for all `ddd:*` generators.
- [Advanced Usage](docs/ADVANCED_USAGE.md) — Nested objects, subdomains, custom resolvers, application layers.

### Building with DDD
- [Real-World Examples](docs/EXAMPLES.md) — Complete working domains (Invoicing, Payment, testing).
- [Events & Listeners](docs/EVENTS_AND_LISTENERS.md) — Event-driven architecture and decoupled domains.
- [Migrating Existing Apps](docs/MIGRATION.md) — Step-by-step guide to refactor traditional Laravel apps.

### Customization & Optimization
- [Customizing Stubs](docs/STUBS.md) — Publish and override generated file templates.
- [Autoloading & Discovery](docs/AUTOLOADING.md) — Auto-register providers, commands, policies, factories, migrations, listeners.
- [Performance & Optimization](docs/PERFORMANCE.md) — Caching, query optimization, autoloading optimization.

### Quality & Security
- [Security Best Practices](docs/SECURITY.md) — Authorization, validation, encryption, audit logging.
- [Testing](docs/TESTING.md) — Testing domain objects and best practices.

### Reference
- [Version Compatibility](docs/VERSION_COMPATIBILITY.md) — Laravel & PHP version matrix and upgrading.
- [Troubleshooting](docs/TROUBLESHOOTING.md) — Common issues and solutions.
- [FAQ](docs/FAQ.md) — Frequently asked questions.
- [Contributing](docs/CONTRIBUTING.md) — How to contribute stubs, features, or fixes.

## Key Features

- ✅ Generate domain objects outside `App\*` with `ddd:*` commands.
- ✅ Support for nested objects and subdomains (dot notation).
- ✅ Configurable application layer (controllers, requests, middleware).
- ✅ Custom layers (Infrastructure, Integrations, etc).
- ✅ Auto-discovery of providers, commands, policies, factories, migrations, listeners.
- ✅ Event-driven architecture with decoupled domains.
- ✅ Production autoloading optimization.
- ✅ Customizable stubs.

## Version Compatibility

| Laravel | LaravelDDD | PHP | Status |
|---------|-----------|-----|--------|
| 11.x – 13.x | 3.x | 8.3+ | **Latest** |
| 11.44.x+ | 2.x | 8.3+ | Current |
| 9.x – 11.x | 0.x – 1.x | 8.1+ | Legacy |

See [UPGRADING](UPGRADING.md) for migration details.

## Example: Invoicing Domain

```bash
# Generate domain structure
php artisan ddd:model Invoicing:Invoice -m
php artisan ddd:value Invoicing:DollarAmount
php artisan ddd:action Invoicing:CreateInvoice
php artisan ddd:event Invoicing:InvoiceCreated
php artisan ddd:listener Invoicing:HandleInvoiceCreated
```

```php
// app/Modules/Invoicing/Controllers/InvoiceController.php
class InvoiceController
{
    public function store(Request $request, CreateInvoice $action)
    {
        $invoice = $action->execute($request->validated());
        return response()->json($invoice);
    }
}
```

See [Real-World Examples](docs/EXAMPLES.md) for full working examples.

## Credits

- [Jasper Tey](https://github.com/JasperTey)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
