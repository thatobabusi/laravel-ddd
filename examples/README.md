# Laravel-DDD Examples

Executable, production-ready examples demonstrating Laravel-DDD patterns.

## Structure

```
examples/
├─ invoicing/          # Complete invoicing domain
│  ├─ DollarAmount.php        # Value object
│  ├─ Invoice.php             # Domain model
│  ├─ CreateInvoiceAction.php # Action: create invoice
│  ├─ SendInvoiceAction.php   # Action: send invoice
│  ├─ InvoiceCreatedEvent.php # Domain event
│  ├─ InvoiceSentEvent.php    # Domain event
│  ├─ InvoiceController.php   # Application layer
│  ├─ StoreInvoiceRequest.php # Form validation
│  ├─ DollarAmountTest.php    # Tests
│  ├─ CreateInvoiceTest.php   # Tests
│  ├─ migration.php           # DB schema
│  ├─ routes.php              # API routes
│  └─ README.md               # This readme (detailed)
│
└─ payment/              # Payment domain with subdomains
   ├─ PaymentStatus.php          # Shared enum
   ├─ SubmitPaymentAction.php    # Customer-facing action
   ├─ README.md                  # This readme (subdomain pattern)
   └─ (more files... see payment/README.md)
```

## How to Use These Examples

1. **As Reference**
   - Read `docs/EXAMPLES.md` for detailed explanations
   - Look at these files for concrete implementations

2. **As Starting Point**
   - Copy files into your project's `app/` or `src/` structure
   - Adjust namespaces to match your project
   - Run tests to verify behavior

3. **As Learning Tool**
   - Study the separation of concerns:
     - **Domain:** Models, Value Objects, Actions, Events
     - **Application:** Controllers, Requests, ViewModels
     - **Infrastructure:** Migrations, Routes, Service Providers

## Key Patterns Demonstrated

### Value Objects
- See: `examples/invoicing/DollarAmount.php`
- Immutable, encapsulates validation, provides arithmetic

### Domain Models
- See: `examples/invoicing/Invoice.php`
- Represents state, encapsulates basic operations

### Actions
- See: `examples/invoicing/CreateInvoiceAction.php`
- Encapsulates business logic, transactional, dispatches events

### Events & Listeners
- See: `examples/invoicing/InvoiceCreatedEvent.php`
- Represents facts, not instructions
- Other domains can subscribe and react

### Subdomains
- See: `examples/payment/`
- Nested domains (`Payment.Internal`, `Payment.Customer`)
- Separates staff vs customer concerns

### Application Layer
- See: `examples/invoicing/InvoiceController.php`
- Handles HTTP only, delegates to domain actions

### Validation
- See: `examples/invoicing/StoreInvoiceRequest.php`
- Form request validation before domain layer

### Testing
- See: `examples/invoicing/*Test.php`
- Tests focus on domain logic, not HTTP
- Value objects and actions are unit-testable

## Running the Examples

To test these examples in a fresh Laravel project:

1. Copy files to appropriate locations:
   - `app/Modules/Invoicing/` (controllers, requests)
   - `src/Domain/Invoicing/` (models, value objects, actions, events)
   - `tests/Unit/Domain/Invoicing/` (tests)
   - `database/migrations/` (migration file)

2. Run migrations:
   ```bash
   php artisan migrate
   ```

3. Run tests:
   ```bash
   composer test
   ```

4. Try the API endpoints (see routes.php)

## Next Steps

- Add more domains (User, Product, etc.)
- Implement event listeners in other domains
- Add comprehensive test suites
- Add API documentation (OpenAPI/Swagger)
- Add performance optimizations (caching, queues)

See the full documentation in `docs/` for deeper dives into each pattern.

--- 

*These examples align with the patterns documented in:*
- `docs/EXAMPLES.md` — Detailed walkthrough
- `docs/ADVANCED_USAGE.md` — Nested objects, subdomains, custom resolvers
- `docs/EVENTS_AND_LISTENERS.md` — Event-driven architecture
- `docs/TESTING.md` — Testing domain objects