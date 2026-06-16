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

## Scaffold a Bounded-Context (New!)

Generate a complete domain structure with all layers:

```bash
php artisan ddd:make:domain UserManagement
php artisan ddd:eloquent-model UserManagement:User
php artisan ddd:repository UserManagement:UserRepository
php artisan ddd:mapper UserManagement:UserMapper
php artisan ddd:policy UserManagement:UserPolicy
php artisan ddd:provider UserManagement:UserManagementServiceProvider
php artisan ddd:command-query UserManagement:CreateUserCommand
php artisan ddd:command-query UserManagement:GetUserQuery --query
```

See [Scaffolding Guide](docs/SCAFFOLDING.md) for complete workflow.

## Documentation

Complete documentation is organized into focused guides:

### Getting Started
- [Installation & Setup](docs/INSTALLATION.md) — Install, initialize, and deploy.
- [Configuration](docs/CONFIGURATION.md) — Configure domains, layers, and namespaces.

### Using the Toolkit
- [Available Commands](docs/COMMANDS.md) — Reference for all `ddd:*` generators.
- [Advanced Usage](docs/ADVANCED_USAGE.md) — Nested objects, subdomains, custom resolvers, application layers.
- [Scaffolding & Domain Structure](docs/SCAFFOLDING.md) — Bounded-contexts, layered architecture, CQRS patterns. **NEW!**

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
- ✅ Scaffold bounded-contexts with all four layers (`Domain`, `Application`, `Presentation`, `Infrastructure`).
- ✅ Generate Eloquent models, mappers, repositories, policies, and service providers.
- ✅ CQRS support: Commands and Queries with dedicated generators.
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

## Example: Complete Domain

```bash
# Scaffold bounded-context
php artisan ddd:make:domain Invoicing

# Generate infrastructure layer
php artisan ddd:eloquent-model Invoicing:Invoice -m
php artisan ddd:eloquent-model Invoicing:LineItem -m
php artisan ddd:repository Invoicing:InvoiceRepository
php artisan ddd:mapper Invoicing:InvoiceMapper

# Generate application layer
php artisan ddd:command-query Invoicing:CreateInvoiceCommand
php artisan ddd:command-query Invoicing:GetInvoiceQuery --query

# Generate domain layer
php artisan ddd:value Invoicing:DollarAmount
php artisan ddd:policy Invoicing:InvoicePolicy
php artisan ddd:event Invoicing:InvoiceCreated
php artisan ddd:listener Invoicing:HandleInvoiceCreated

# Bind everything
php artisan ddd:provider Invoicing:InvoicingServiceProvider
```

See [Real-World Examples](docs/EXAMPLES.md) and [Scaffolding Guide](docs/SCAFFOLDING.md) for full working examples.

## Credits

- [Jasper Tey](https://github.com/JasperTey)
- [Orphail DDD Inspiration](https://github.com/Orphail/laravel-ddd)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
