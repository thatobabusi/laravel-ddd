# Domain Driven Design Toolkit for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/thatobabusi/laravel-ddd.svg?style=flat-square)](https://packagist.org/packages/thatobabusi/laravel-ddd)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/thatobabusi/laravel-ddd/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/thatobabusi/laravel-ddd/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/thatobabusi/laravel-ddd/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/thatobabusi/laravel-ddd/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/thatobabusi/laravel-ddd.svg?style=flat-square)](https://packagist.org/packages/thatobabusi/laravel-ddd)

Laravel-DDD is a toolkit to support domain-driven design (DDD) in Laravel applications. One of the pain points when adopting DDD is the inability to use Laravel's native `make` commands to generate objects outside the `App\*` namespace. This package aims to fill the gaps by providing equivalent commands such as `ddd:model`, `ddd:dto`, `ddd:view-model` and many more.

## Quick Start

```bash
composer require thatobabusi/laravel-ddd
php artisan ddd:install
php artisan ddd:config wizard
```

## All-in-One Feature Wizard (NEW!)

**Generate a complete DDD feature in seconds:**

```bash
php artisan ddd:make:feature ForUserLogin --folder=Authentication
```

**Generates (10+ files automatically):**
- Request class with validation
- Action (invokable controller)
- UseCase (interface + implementation)
- Domain Service (interface + implementation)
- Repository (interface + implementation)
- Output DTO
- Response (interface + implementation)
- **Auto-prints:** Exact binding code for `AppServiceProvider::register()`
- **Auto-prints:** Exact route definition for `routes/api.php`
- **All files include TODO markers** showing exactly what to implement

See [Quick Start Wizard](docs/QUICK-START-WIZARD.md) for complete guide, real-world examples, and advanced options.

## Scaffold a Bounded-Context (Traditional Approach)

Alternatively, use granular commands for full control:

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

## Command Comparison

| Approach | Speed | Flexibility | Learning Curve |
|----------|-------|-------------|----------------|
| **Feature Wizard** `ddd:make:feature` | ⚡ 2 min | Good (answer 9 questions) | Beginner |
| **Granular Commands** `ddd:*` | 🐢 10 min | Excellent (build incrementally) | Intermediate |

**Use the wizard for:** Creating a feature from scratch quickly.  
**Use granular commands for:** Fine-grained control, incremental building, or customization.


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

- ✅ **All-in-one feature wizard:** `ddd:make:feature` generates complete DDD features with intelligent prompting
- ✅ **TODO-driven scaffolding:** All generated files have clear markers showing what to implement
- ✅ **Auto-bindings & routes:** Wizard prints exact code for AppServiceProvider and routes/api.php
- ✅ **11 generators:** domain, eloquent-model, mapper, repository, policy, provider, command, query, use-case, response, action
- ✅ **Generate domain objects** outside `App\*` with `ddd:*` commands
- ✅ **Support for nested objects** and subdomains (dot notation)
- ✅ **Configurable application layer** (controllers, requests, middleware)
- ✅ **Custom layers** (Infrastructure, Integrations, etc)
- ✅ **Auto-discovery** of providers, commands, policies, factories, migrations, listeners
- ✅ **Event-driven architecture** with decoupled domains
- ✅ **Production autoloading optimization**
- ✅ **Customizable stubs**

## Version Compatibility

| Laravel | LaravelDDD | PHP | Status |
|---------|-----------|-----|--------|
| 11.x – 13.x | 3.x | 8.3+ | **Latest** |
| 11.44.x+ | 2.x | 8.3+ | Current |
| 9.x – 11.x | 0.x – 1.x | 8.1+ | Legacy |

See [UPGRADING](UPGRADING.md) for migration details.

## Example: Complete Feature (Feature Wizard)

```bash
# Interactive wizard
php artisan ddd:make:feature ForCreatePost --folder=Blog

# Or with options
php artisan ddd:make:feature ForCreatePost \
  --folder=Blog \
  --with-entity \
  --with-eloquent-model
```

**Output:**
```
✔ CREATED app/Http/Requests/Api/V1/Blog/ForCreatePostRequest.php
✔ CREATED app/Http/Controllers/Api/V1/Blog/ForCreatePostAction.php
✔ CREATED app/UseCases/Blog/IForCreatePostUseCase.php
✔ CREATED app/UseCases/Blog/ForCreatePostUseCase.php
✔ CREATED app/Domain/Blog/Services/IForCreatePostService.php
✔ CREATED app/Infra/Blog/Services/ForCreatePostService.php
✔ CREATED app/Domain/Blog/Repositories/IForCreatePostRepository.php
✔ CREATED app/Infra/Blog/Repositories/ForCreatePostRepository.php
✔ CREATED app/Domain/Blog/Services/Output/ForCreatePostOutput.php
✔ CREATED app/Http/Responses/Api/V1/Blog/IForCreatePostResponse.php
✔ CREATED app/Http/Responses/Api/V1/Blog/ForCreatePostResponse.php

── Add to AppServiceProvider::register() ─────────────────────
$this->app->bind(App\UseCases\Blog\IForCreatePostUseCase::class, ...);
$this->app->bind(App\Domain\Blog\Services\IForCreatePostService::class, ...);
$this->app->bind(App\Domain\Blog\Repositories\IForCreatePostRepository::class, ...);
$this->app->bind(App\Http\Responses\Api\V1\Blog\IForCreatePostResponse::class, ...);

── Add to routes/api.php ─────────────────────────────────────
Route::post('/for-create-post', \App\Http\Controllers\Api\V1\Blog\ForCreatePostAction::class);

Done! All files generated successfully.
```

Each generated file has **TODO comments** showing exactly what to implement.

See [Real-World Examples](docs/EXAMPLES.md) and [Quick Start Wizard](docs/QUICK-START-WIZARD.md) for complete walkthrough.

## Example: Granular Approach (Full Control)

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


## Credits

- [Jasper Tey](https://github.com/JasperTey) - [Laravel DDD](https://github.com/jaspertey/laravel-ddd)
- [Dani Martinez](https://github.com/Orphail) - [Orphail DDD](https://github.com/Orphail/laravel-ddd)
- [Imran Ahmed](https://github.com/imran-ahmed-optilius) - [Ahmed Laravel DDD Maker](https://github.com/imran-ahmed-optilius/laravel-ddd-maker)
- [Spatie](https://github.com/spatie) - [Laravel packages best practices](https://github.com/spatie/package-skeleton-laravel)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
