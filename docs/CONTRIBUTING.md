# Contributing to Laravel-DDD

## Overview
Contributions are welcome. This guide outlines the process.

## Code Style
- Follow PSR-12 conventions.
- Use type hints on all methods.
- Write descriptive commit messages.

## Stubs
Laravel-DDD ships with DDD-specific stubs. To contribute:
1. Edit stubs in `src/Console/Stubs/ddd/*.stub`.
2. Test with `php artisan ddd:stub --all`.
3. Document changes in stub comments.

## Testing
All new features must include tests:
```bash
composer test
```

## Pull Request Process
1. Fork the repository.
2. Create a feature branch: `git checkout -b feature/my-feature`.
3. Make changes and add tests.
4. Run tests: `composer test`.
5. Commit with clear messages.
6. Push and open a PR with a description of changes.

## Reporting Issues
Issues should include:
- Laravel version
- LaravelDDD version
- PHP version
- Steps to reproduce
- Expected vs actual behavior

## Security Vulnerabilities
See [Security Policy](../../security/policy) for reporting process.

## Credits
See [Credits](../README.md#credits) for list of contributors.
