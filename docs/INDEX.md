# Laravel-DDD Documentation Index

Complete, navigable reference for all documentation.

## 🆕 What's New in v4.0?

**Feature Wizard:** Generate complete DDD features in seconds with intelligent prompting.
```bash
php artisan ddd:make:feature ForUserLogin --folder=Authentication
```
Automatically creates Request + Action + UseCase + Service + Repository + DTO + Response with TODO markers and auto-printed bindings. See [Quick Start Wizard](QUICK-START-WIZARD.md).

**New Generators:**
- `ddd:make:feature` — All-in-one feature scaffolding
- `ddd:make:domain` — Bounded-context with 4 layers
- `ddd:use-case` — UseCase interface + implementation
- `ddd:response` — Response interface + implementation
- `ddd:service` — Service interface + implementation
- `ddd:action` — Domain actions
- `ddd:eloquent-model`, `ddd:repository`, `ddd:mapper`, `ddd:policy`, `ddd:provider`, `ddd:command-query` — Existing generators now working smoothly

**20+ New Documentation Files:**
- Comprehensive guides for all features
- Real-world examples (Invoicing, Payment domains)
- Security, performance, and migration guides

---

## Quick Navigation

### 🚀 Getting Started (5-10 min)
1. [Installation & Setup](INSTALLATION.md) — Install and initialize the package.
2. [Quick Start Wizard](QUICK-START-WIZARD.md) ⭐ **NEW** — Generate your first feature in 2 minutes.
3. [Configuration](CONFIGURATION.md) — Configure domains, layers, and namespaces.

### 💻 Core Usage (15-30 min)
4. [Available Commands](COMMANDS.md) — Reference for all `ddd:*` commands.
5. [Scaffolding & Domain Structure](SCAFFOLDING.md) — Bounded-contexts, layered architecture, granular approach.
6. [Advanced Usage](ADVANCED_USAGE.md) — Nested objects, subdomains, custom resolvers, layers.

### 🏗️ Building with DDD (30-60 min)
7. [Real-World Examples](EXAMPLES.md) — Complete working domains (Invoicing, Payment, testing).
8. [Events & Listeners](EVENTS_AND_LISTENERS.md) — Event-driven architecture and decoupled domains.
9. [Migrating Existing Apps](MIGRATION.md) — Step-by-step refactoring guide.

### ⚙️ Optimization & Customization (15-25 min)
10. [Customizing Stubs](STUBS.md) — Publish and override generated templates.
11. [Autoloading & Discovery](AUTOLOADING.md) — Auto-discovery of providers, commands, policies, etc.
12. [Performance & Optimization](PERFORMANCE.md) — Query optimization, caching, production tuning.

### 🔒 Quality & Security (10-20 min)
13. [Security Best Practices](SECURITY.md) — Authorization, validation, encryption, audit logging.
14. [Testing](TESTING.md) — Testing domain objects and patterns.

### 📚 Reference & Support (10-15 min)
15. [Version Compatibility](VERSION_COMPATIBILITY.md) — Laravel/PHP matrix and upgrading.
16. [Troubleshooting](TROUBLESHOOTING.md) — Common issues and solutions.
17. [FAQ](FAQ.md) — Frequently asked questions.
18. [Contributing](CONTRIBUTING.md) — How to contribute.

---

## By Use Case

### I'm starting a new DDD project
1. [Installation & Setup](INSTALLATION.md)
2. [Quick Start Wizard](QUICK-START-WIZARD.md)
3. [Configuration](CONFIGURATION.md)
4. [Real-World Examples](EXAMPLES.md)

### I want to build a feature fast
1. [Quick Start Wizard](QUICK-START-WIZARD.md)
2. Done! Fill in TODOs.

### I want to learn DDD patterns
1. [Real-World Examples](EXAMPLES.md)
2. [Scaffolding & Domain Structure](SCAFFOLDING.md)
3. [Events & Listeners](EVENTS_AND_LISTENERS.md)
4. [Advanced Usage](ADVANCED_USAGE.md)

### I'm refactoring existing code to DDD
1. [Migrating Existing Apps](MIGRATION.md)
2. [Scaffolding & Domain Structure](SCAFFOLDING.md)
3. [Real-World Examples](EXAMPLES.md)
4. [Testing](TESTING.md)

### I need to optimize for production
1. [Performance & Optimization](PERFORMANCE.md)
2. [Autoloading & Discovery](AUTOLOADING.md)
3. [Security Best Practices](SECURITY.md)

### Something isn't working
1. [Troubleshooting](TROUBLESHOOTING.md)
2. [FAQ](FAQ.md)

---

## Document Categories

| Category | Files | Time |
|----------|-------|------|
| **Getting Started** | Installation, Configuration, Wizard | 10 min |
| **Core Usage** | Commands, Scaffolding, Advanced | 30 min |
| **Building** | Examples, Events, Migration | 60 min |
| **Optimization** | Stubs, Autoloading, Performance | 25 min |
| **Quality** | Security, Testing | 20 min |
| **Reference** | Compatibility, Troubleshooting, FAQ | 15 min |

---

## Estimated Reading Times

- **Complete guide:** 2–3 hours
- **Essentials (Getting Started + Wizard + Examples):** 45 minutes
- **Quick reference:** 10 minutes (command list)

---

## Contributing to Docs

Found an error or want to improve docs? See [Contributing](CONTRIBUTING.md).
