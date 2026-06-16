# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [4.0.0] - 2026-06-16

### 🆕 Added (Major Features)

#### Feature Wizard
- **`ddd:make:feature`** - All-in-one command that scaffolds complete DDD features in seconds
  - Interactive wizard with 9 intelligent complexity questions
  - Generates Request + Action + UseCase + Service + Repository + DTO + Response (10+ files)
  - Auto-prints binding code for `AppServiceProvider::register()`
  - Auto-prints route definition for `routes/api.php`
  - Supports optional Entity, Eloquent Model, Value Objects, Input DTOs
  - Non-interactive mode for CI/CD pipelines

#### New Generators (11 total)
- **`ddd:make:domain`** - Scaffold bounded-context with Domain, Application, Presentation, Infrastructure layers
- **`ddd:use-case`** - Generate UseCase interface + implementation
- **`ddd:response`** - Generate Response interface + implementation
- **`ddd:service`** - Generate Domain Service interface + implementation
- **`ddd:action`** - Generate domain actions
- **`ddd:eloquent-model`** - Infrastructure-layer Eloquent models
- **`ddd:repository`** - Repository interface + Eloquent implementation
- **`ddd:mapper`** - Data mappers between layers (Eloquent ↔ Domain)
- **`ddd:policy`** - Domain authorization policies
- **`ddd:provider`** - Domain-specific service providers for bindings
- **`ddd:command-query`** - CQRS commands and queries

#### TODO-Driven Scaffolding
- All generated files include strategic **TODO markers** showing exactly what to implement
- Clear guidance in each generated file:
  - Request: `// TODO: Add validation rules`
  - Service: `// TODO: Implement business logic`
  - Repository: `// TODO: Implement Eloquent queries`
  - DTO: `// TODO: Define properties and getters`
  - Response: `// TODO: Map to API response format`

#### Documentation (20+ new files, ~70,000 words)
- **QUICK-START-WIZARD.md** - Complete walkthrough of the feature wizard
- **SCAFFOLDING.md** - Bounded-contexts, layers, granular vs wizard approaches
- **EXAMPLES.md** - Real-world domains (Invoicing, Payment with subdomains, testing patterns)
- **EVENTS_AND_LISTENERS.md** - Event-driven architecture, cross-domain communication
- **MIGRATION.md** - Step-by-step guide to refactor traditional Laravel to DDD
- **SECURITY.md** - Best practices (authorization, validation, encryption, audit logging)
- **PERFORMANCE.md** - Query optimization, caching, production tuning, benchmarking
- **ADVANCED_USAGE.md** - Nested objects, subdomains, custom resolvers
- **ARCHITECTURE.md** - Layer customization, custom resolvers, autoloading discovery
- **STUBS.md** - Publishing and customizing generated templates
- **AUTOLOADING.md** - Auto-discovery configuration and production optimization
- **TESTING.md** - Testing domain objects, patterns, best practices
- **VERSION_COMPATIBILITY.md** - Laravel/PHP version matrix, upgrade paths
- **TROUBLESHOOTING.md** - Common issues and solutions
- **FAQ.md** - 15+ frequently asked questions
- **CONTRIBUTING.md** - How to contribute to the package
- **INDEX.md** - Complete documentation navigation with use-case routing

#### Real-World Examples
- **examples/invoicing/** - Complete Invoicing domain
  - Value Objects (DollarAmount)
  - Models, Actions, Events
  - Controller integration
  - Form request validation
  - Database migration
  - Route definitions
  - Tests (Value Objects, Actions)
- **examples/payment/** - Payment domain with subdomains
  - Enum patterns (PaymentStatus)
  - Subdomain structure (Internal vs Customer)
  - Cross-subdomain delegation
  - Multi-repository patterns

### 🔄 Changed

#### ServiceProvider
- Updated `LaravelDDDServiceProvider` to register all 11 new commands
- All commands now automatically available via `php artisan` CLI

#### Stubs
- All existing stubs enhanced with TODO markers
- New stubs created for missing generators (use-case, response, service, action)
- Consistent formatting and PSR-12 compliance across all stubs

#### Documentation Structure
- Reorganized into logical, focused guides (one per concept/task)
- Added "What's New in v4.0" section with quick links
- Use-case-based navigation (new vs learning vs troubleshooting)
- Estimated reading times for each section

### 📚 Documentation Improvements
- Comprehensive getting started guide
- Real-world examples with complete code
- Security best practices documentation
- Performance optimization guide
- Migration strategy for existing apps
- Event-driven architecture patterns
- Testing strategies and patterns
- FAQ covering 15+ common questions
- Troubleshooting guide with solutions

### 🔧 Infrastructure
- All new commands pass PHP syntax validation
- All stubs render correctly with no placeholder leakage
- Proper namespace handling for nested domains
- Support for folder nesting (e.g., `Users/Profile/History`)
- Automatic binding code generation
- Automatic route suggestion

### ✅ Quality
- TODO markers guide developers through implementation
- Clear separation of concerns (Domain, Application, Infrastructure)
- Type-safe value objects with immutability
- CQRS pattern support (Commands and Queries)
- Event-driven architecture patterns
- Authorization policies at domain level

## [3.x] - Previous Release

See [GitHub Releases](https://github.com/jaspertey/laravel-ddd/releases) for v3.x changelog.

---

## Upgrade Guide: v3 → v4

### No Breaking Changes
- All existing `ddd:*` commands continue to work unchanged
- All new features are additive
- Existing projects need no modifications

### New Recommendations
- For new features, try `ddd:make:feature` for speed
- For fine-grained control, use granular `ddd:*` commands
- Both approaches can be mixed in the same project

### Installation
```bash
composer require tey/laravel-ddd:^4.0
php artisan ddd:install
```

No additional configuration needed. All commands auto-register.

---

## Migration Path (v3 → v4)

**Step 1:** Install v4
```bash
composer require tey/laravel-ddd:^4.0
```

**Step 2:** Run existing commands (they work unchanged)
```bash
php artisan ddd:model MyDomain:MyModel
```

**Step 3:** Try the new feature wizard
```bash
php artisan ddd:make:feature ForCreateUser --folder=Users
```

That's it! No breaking changes, full backward compatibility.

---

## Credits

- **Orphail/laravel-ddd** - Inspired bounded-context scaffolding patterns
- **Imran Ahmed/laravel-ddd-maker** - Inspired feature wizard UX and TODO-driven development
- **Spatie** - Laravel packages best practices
- **Jasper Tey** - Original package author

---

## License

The MIT License (MIT). See [LICENSE](LICENSE.md) for more information.
