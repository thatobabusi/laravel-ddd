# Laravel-DDD Documentation Index

Complete, navigable reference for all documentation.

## Quick Navigation

### 🚀 Getting Started (5 min)
1. [Installation & Setup](INSTALLATION.md) — Install and initialize the package.
2. [Configuration](CONFIGURATION.md) — Configure domains, layers, and generate stubs.

### 💻 Core Usage (15 min)
3. [Available Commands](COMMANDS.md) — Reference for all `ddd:*` commands.
4. [Advanced Usage](ADVANCED_USAGE.md) — Nested objects, subdomains, custom resolvers, layers.
5. [Scaffolding & Domain Structure](SCAFFOLDING.md) — **NEW!** Bounded-contexts, layered architecture, CQRS.

### 🏗️ Building with DDD (30-45 min)
6. [Real-World Examples](EXAMPLES.md) — Complete working domains (Invoicing, Payment, testing).
7. [Events & Listeners](EVENTS_AND_LISTENERS.md) — Event-driven architecture and decoupled domains.
8. [Migrating Existing Apps](MIGRATION.md) — Step-by-step refactoring guide.

### ⚙️ Optimization & Customization (20 min)
9. [Customizing Stubs](STUBS.md) — Publish and override generated templates.
10. [Autoloading & Discovery](AUTOLOADING.md) — Auto-discovery of providers, commands, policies, etc.
11. [Performance & Optimization](PERFORMANCE.md) — Query optimization, caching, production tuning.

### 🔒 Quality & Security (15 min)
12. [Security Best Practices](SECURITY.md) — Authorization, validation, encryption, audit logging.
13. [Testing](TESTING.md) — Testing domain objects and patterns.

### 📚 Reference & Support (10 min)
14. [Version Compatibility](VERSION_COMPATIBILITY.md) — Laravel/PHP matrix and upgrading.
15. [Troubleshooting](TROUBLESHOOTING.md) — Common issues and solutions.
16. [FAQ](FAQ.md) — Frequently asked questions.
17. [Contributing](CONTRIBUTING.md) — How to contribute.

---

## By Use Case

### I'm starting a new DDD project
1. [Installation & Setup](INSTALLATION.md)
2. [Configuration](CONFIGURATION.md)
3. [Scaffolding & Domain Structure](SCAFFOLDING.md)
4. [Real-World Examples](EXAMPLES.md)

### I want to build a complete bounded-context
1. [Scaffolding & Domain Structure](SCAFFOLDING.md)
2. [Real-World Examples](EXAMPLES.md)
3. [Testing](TESTING.md)

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
| **Getting Started** | Installation, Configuration, Scaffolding | 5 min |
| **Core Usage** | Commands, Advanced Usage | 15 min |
| **Building** | Examples, Events, Migration | 45 min |
| **Optimization** | Stubs, Autoloading, Performance | 20 min |
| **Quality** | Security, Testing | 15 min |
| **Reference** | Compatibility, Troubleshooting, FAQ | 10 min |

---

## New to DDD?

Start here for a guided introduction:

1. **What is DDD?** → Read [Real-World Examples](EXAMPLES.md)
2. **How does it work in Laravel?** → Follow [Installation & Setup](INSTALLATION.md)
3. **Show me the structure** → Jump to [Scaffolding & Domain Structure](SCAFFOLDING.md)
4. **How do I scaffold?** → See the commands in [Scaffolding Guide](SCAFFOLDING.md)
5. **Complete example** → Review [Real-World Examples](EXAMPLES.md)
6. **What about events?** → Read [Events & Listeners](EVENTS_AND_LISTENERS.md)

---

## Common Tasks

### Generate a new bounded-context
→ [Scaffolding & Domain Structure](SCAFFOLDING.md) → `ddd:make:domain`

### Create an Eloquent model (Infrastructure)
→ [Scaffolding & Domain Structure](SCAFFOLDING.md) → `ddd:eloquent-model`

### Create a repository with interface
→ [Scaffolding & Domain Structure](SCAFFOLDING.md) → `ddd:repository`

### Create a mapper (transform data between layers)
→ [Scaffolding & Domain Structure](SCAFFOLDING.md) → `ddd:mapper`

### Create a command or query (CQRS)
→ [Scaffolding & Domain Structure](SCAFFOLDING.md) → `ddd:command-query`

### Create a service provider for bindings
→ [Scaffolding & Domain Structure](SCAFFOLDING.md) → `ddd:provider`

### Add validation to my DTO
→ [Real-World Examples](EXAMPLES.md) → DTO Validation section

### Test my domain object
→ [Testing](TESTING.md)

### Move my existing app to DDD
→ [Migrating Existing Apps](MIGRATION.md)

### Optimize autoloading in production
→ [Performance & Optimization](PERFORMANCE.md) → Production Autoloading

### Handle cross-domain communication
→ [Events & Listeners](EVENTS_AND_LISTENERS.md) → Cross-Domain Communication

### Customize how objects are generated
→ [Customizing Stubs](STUBS.md)

### Find out which version I need
→ [Version Compatibility](VERSION_COMPATIBILITY.md)

### Troubleshoot an issue
→ [Troubleshooting](TROUBLESHOOTING.md) or [FAQ](FAQ.md)

---

## Quick Links

- [Main README](../README.md) — Package overview
- [UPGRADING](../UPGRADING.md) — Version upgrade guide
- [CHANGELOG](../CHANGELOG.md) — What's new
- [License](../LICENSE.md) — MIT License

---

## Document Relationships

```
Installation → Configuration → Scaffolding
    ↓
Commands (Reference)
    ↓
Advanced Usage ← Real-World Examples
    ↓
Events & Listeners
    ↓
Testing ← Migrating Existing Apps
    ↓
Performance & Optimization
    ↓
Security Best Practices
    ↓
Troubleshooting / FAQ
```

---

## Estimated Reading Times

- **Complete guide:** 2–3 hours
- **Essentials (Getting Started + Scaffolding + Examples):** 45 minutes
- **Quick reference:** 10 minutes (command list)

---

## What's New?

### 📦 Phase 5 Additions (Orphail-Inspired)
- **`ddd:make:domain`** — Scaffold bounded-contexts with all four layers
- **`ddd:eloquent-model`** — Create infrastructure-layer Eloquent models
- **`ddd:repository`** — Generate repository interface + implementation
- **`ddd:mapper`** — Create data mappers between layers
- **`ddd:policy`** — Generate domain authorization policies
- **`ddd:provider`** — Create service providers for dependency binding
- **`ddd:command-query`** — Generate CQRS commands and queries
- **[Scaffolding & Domain Structure](SCAFFOLDING.md)** — Complete guide to the new commands

---

## Contributing to Docs

Found an error or want to improve docs? See [Contributing](CONTRIBUTING.md).
