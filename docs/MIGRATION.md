# Migrating Existing Laravel Apps to DDD

Guide for refactoring traditional Laravel applications to domain-driven design.

## Assessment Phase

### Identify Domain Boundaries
Map your current application to potential domains:

```
Current Structure        →    Domain Structure
─────────────────────────────────────────────────
App\Models\Invoice      →    Domain\Invoicing\Models\Invoice
App\Models\Payment      →    Domain\Payment\Models\Payment
App\Models\Customer     →    Domain\Shared\Models\Customer
App\Requests\*          →    App\Modules\{Domain}\Requests\*
App\Controllers\*       →    App\Modules\{Domain}\Controllers\*
```

### Analyze Dependencies
Document cross-model dependencies to define clean domain boundaries:
- Which models interact directly?
- Are there circular dependencies?
- What shared data exists (Customer, Settings, etc.)?

---

## Phase 1: Setup Infrastructure

### 1.1 Install & Initialize
```bash
composer require tey/laravel-ddd
php artisan ddd:install
php artisan ddd:config wizard
```

### 1.2 Define Your Domains
Decide on domain structure. Example:
- **Shared** — Customer, User, Settings
- **Invoicing** — Invoices, LineItems, InvoicePeriods
- **Payment** — Transactions, Refunds, PaymentMethods
- **Reporting** — Analytics, Charts, Exports

### 1.3 Create Shared Domain
Most apps need a shared domain for cross-cutting entities:

```bash
php artisan ddd:model Shared:Customer
php artisan ddd:model Shared:User
php artisan ddd:model Shared:Settings
```

---

## Phase 2: Migrate a Single Domain (Pilot)

Start with one non-critical domain to test the process.

### 2.1 Create Domain Models
```bash
# Generate domain models (copy from App\Models\Invoice)
php artisan ddd:model Invoicing:Invoice -m
php artisan ddd:model Invoicing:LineItem -m
```

### 2.2 Copy Model Code
Copy logic from old models to new domain models:

```php
// OLD: App\Models\Invoice
public function getFormattedAmountAttribute() { ... }

// NEW: Domain\Invoicing\Models\Invoice
public function getFormattedAmountAttribute() { ... }
```

### 2.3 Extract Value Objects
Identify and extract value objects from model methods:

```php
// OLD: Invoice model has currency logic scattered
public function formatAmount($currency = 'USD') { ... }

// NEW: Extract to value object
php artisan ddd:value Invoicing:Money
```

### 2.4 Create Actions
Extract business logic from controllers into domain actions:

```bash
php artisan ddd:action Invoicing:CreateInvoice
php artisan ddd:action Invoicing:SendInvoiceToCustomer
php artisan ddd:action Invoicing:MarkInvoiceAsPaid
```

Refactor controller:
```php
// OLD controller
public function store(Request $request)
{
    $invoice = Invoice::create($request->validated());
    Mail::to($invoice->customer)->send(new InvoiceMail($invoice));
    return response()->json($invoice);
}

// NEW controller
public function store(Request $request, CreateInvoice $action)
{
    $invoice = $action->execute($request->validated());
    return response()->json($invoice);
}
```

### 2.5 Update Routes & Controllers
Update routes to point to domain-aware controllers:

```php
// routes/api.php
Route::apiResource('invoices', App\Modules\Invoicing\Controllers\InvoiceController::class);
```

### 2.6 Test & Verify
Run full test suite to ensure behavior is preserved:
```bash
composer test
```

---

## Phase 3: Parallel Run (Optional)

Run old and new domain code simultaneously to verify correctness:

```php
// In a middleware or action
$oldResult = app(App\Models\Invoice::class)->find($id);
$newResult = app(Domain\Invoicing\Models\Invoice::class)->find($id);

assert($oldResult->id === $newResult->id);
```

---

## Phase 4: Gradual Rollout

### 4.1 Migrate Next Domain
Repeat Phase 2 with a second domain. You'll find patterns:
- Common value object structures
- Reusable action patterns
- Shared exceptions

### 4.2 Update Requests/Validation
Migrate form requests to application layer:

```bash
# Generate in application layer
php artisan ddd:request Invoicing:StoreInvoiceRequest
php artisan ddd:request Invoicing:UpdateInvoiceRequest
```

Copy validation logic:
```php
// OLD: App\Requests\StoreInvoiceRequest
public function rules() { return [...]; }

// NEW: App\Modules\Invoicing\Requests\StoreInvoiceRequest
public function rules() { return [...]; }
```

### 4.3 Update Event Listeners
Migrate event listeners to domain:

```bash
php artisan ddd:listener Invoicing:HandleInvoiceCreated
```

Move listener logic and register in domain provider:
```php
// Domain\Invoicing\Providers\InvoicingServiceProvider
Event::listen(
    InvoiceCreated::class,
    HandleInvoiceCreated::class
);
```

---

## Phase 5: Deprecate Old Code

### 5.1 Mark Old Models as Deprecated
```php
// App\Models\Invoice
/**
 * @deprecated Use Domain\Invoicing\Models\Invoice instead
 */
class Invoice extends Model { ... }
```

### 5.2 Create Compatibility Layer (Optional)
If you need a transition period, create a shim:

```php
// app/Models/Invoice.php
namespace App\Models;

use Domain\Invoicing\Models\Invoice as DomainInvoice;

class Invoice extends DomainInvoice
{
    // Compatibility methods for old code
    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'archived');
    }
}
```

### 5.3 Remove Old Code Gradually
Use static analysis to find remaining references:
```bash
composer require --dev phpstan/phpstan
./vendor/bin/phpstan analyse app
```

---

## Phase 6: Update Tests

### 6.1 Migrate Feature Tests
```php
// OLD test
public function test_invoice_creation()
{
    $response = $this->post('/invoices', [
        'customer_id' => 1,
        'amount' => 100,
    ]);
    $response->assertOk();
}

// NEW test (action-level)
public function test_create_invoice_action()
{
    $action = new CreateInvoice();
    $invoice = $action->execute([
        'customer_id' => 1,
        'amount' => 100,
    ]);
    $this->assertNotNull($invoice->id);
}
```

### 6.2 Add Unit Tests for Value Objects
```php
public function test_money_value_object()
{
    $money = Money::fromDollars(99.99);
    $this->assertEquals(9999, $money->cents());
}
```

### 6.3 Update Factories
```bash
php artisan ddd:factory Invoicing:InvoiceFactory
```

Copy factory definitions:
```php
// Domain\Invoicing\Database\Factories\InvoiceFactory
public function definition()
{
    return [
        'customer_id' => Customer::factory(),
        'amount_cents' => $this->faker->numberBetween(1000, 100000),
    ];
}
```

---

## Phase 7: Clean Up & Optimize

### 7.1 Remove App\Models
Once fully migrated, clean up old models:
```bash
rm -rf app/Models
```

### 7.2 Consolidate Namespaces
Ensure consistency across application layer:
```
app/Modules/Invoicing/
├─ Controllers/
├─ Requests/
├─ Resources/
└─ Middleware/
```

### 7.3 Run Optimizer
```bash
php artisan ddd:optimize
composer dump-autoload
```

### 7.4 Update Documentation
- Update README with new structure
- Document domain responsibilities
- Provide examples for common tasks

---

## Migration Checklist

- [ ] Identified domain boundaries
- [ ] Created domain structure
- [ ] Migrated models to domains
- [ ] Extracted value objects
- [ ] Created domain actions
- [ ] Updated controllers
- [ ] Migrated form requests
- [ ] Migrated event listeners
- [ ] Updated tests
- [ ] Verified behavior with tests
- [ ] Removed old code
- [ ] Optimized autoloading
- [ ] Updated documentation

---

## Common Pitfalls

### Over-engineering Domains
**Problem:** Creating too many small domains.  
**Solution:** Start with 3–5 core domains. Combine related concerns.

### Circular Dependencies
**Problem:** Domain A imports from Domain B, which imports from Domain A.  
**Solution:** Extract common code to Shared domain.

### Leaky Abstractions
**Problem:** Actions expose internal model structures.  
**Solution:** Use DTOs and value objects for boundaries.

### Not Testing Domain Code
**Problem:** Tests only exercise HTTP layer.  
**Solution:** Add unit tests for actions, value objects, models.

### Large Migrations
**Problem:** Trying to migrate entire app at once.  
**Solution:** Migrate one domain at a time; parallel run if needed.

---

## Rollback Strategy

If migration goes wrong, you have options:

### 1. Parallel Layer
Keep old code running alongside new:
```php
// Route to new domain (or old if there's an error)
if (config('app.use_ddd')) {
    return app(CreateInvoice::class)->execute($data);
}
return app(App\Invoices\InvoiceService::class)->create($data);
```

### 2. Database Rollback
Keep migrations reversible:
```bash
php artisan migrate:rollback --step=5
```

### 3. Version Control
Use feature branches for migration:
```bash
git checkout -b migrate/ddd-invoicing
```

---

## Key Takeaways

1. **Start small** — migrate one domain at a time.
2. **Test heavily** — maintain test coverage throughout.
3. **Use parallel runs** — validate new code before deprecating old.
4. **Extract gradually** — don't rush value objects or actions.
5. **Document as you go** — update README and code comments.
6. **Keep commits atomic** — one domain per commit sequence.
7. **Involve the team** — migration affects everyone's workflow.
