# Security Best Practices

Securing your Laravel-DDD application at the domain layer.

## Access Control

### Domain-Level Authorization
Use policies to control access to domain operations:

```php
namespace Domain\Invoicing\Policies;

use App\Models\User;
use Domain\Invoicing\Models\Invoice;

class InvoicePolicy
{
    public function view(User $user, Invoice $invoice): bool
    {
        return $user->id === $invoice->user_id || $user->isAdmin();
    }

    public function update(User $user, Invoice $invoice): bool
    {
        return $user->id === $invoice->user_id && !$invoice->isPaid();
    }

    public function delete(User $user, Invoice $invoice): bool
    {
        return $user->isAdmin() && !$invoice->isPaid();
    }
}
```

Register in config:
```php
'autoload' => [
    'policies' => true,
],
```

### Action-Level Guards
Enforce authorization in actions:

```php
namespace Domain\Invoicing\Actions;

use Domain\Invoicing\Models\Invoice;
use Domain\Invoicing\Exceptions\UnauthorizedActionException;

class DeleteInvoice
{
    public function execute(Invoice $invoice, User $user): void
    {
        if (!$user->can('delete', $invoice)) {
            throw new UnauthorizedActionException(
                'You do not have permission to delete this invoice.'
            );
        }

        $invoice->delete();
    }
}
```

---

## Data Validation

### DTO Validation
Use Spatie's Laravel Data for automatic validation:

```php
namespace Domain\Invoicing\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Numeric;

class CreateInvoicePayload extends Data
{
    public function __construct(
        public int $customer_id,
        
        #[Email]
        public string $customer_email,
        
        #[Numeric(min: 0.01)]
        public float $amount,
        
        public string $description,
    ) {}
}
```

### Value Object Validation
Encapsulate validation in value objects:

```php
namespace Domain\Invoicing\ValueObjects;

class InvoiceNumber
{
    public function __construct(private readonly string $value)
    {
        if (!preg_match('/^INV-\d{6}$/', $this->value)) {
            throw new \InvalidArgumentException(
                'Invalid invoice number format. Expected: INV-XXXXXX'
            );
        }
    }

    public static function from(string $value): self
    {
        return new self($value);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
```

---

## Input Sanitization

### Sanitize Domain Input
Clean and normalize data entering the domain:

```php
namespace Domain\Invoicing\Actions;

use Domain\Invoicing\Models\Invoice;
use Illuminate\Support\Str;

class CreateInvoice
{
    public function execute(array $data): Invoice
    {
        return Invoice::create([
            'customer_id' => (int) $data['customer_id'],
            'description' => trim(strip_tags($data['description'])),
            'notes' => $this->sanitizeMarkdown($data['notes'] ?? ''),
            'reference' => Str::slug($data['reference'] ?? ''),
        ]);
    }

    private function sanitizeMarkdown(string $input): string
    {
        // Use a markdown sanitizer library
        return \League\HTMLToMarkdown\HtmlConverter::convert(
            strip_tags($input)
        );
    }
}
```

---

## Mass Assignment Protection

### Model Security
Always use `fillable` or `guarded` on domain models:

```php
namespace Domain\Invoicing\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'customer_id',
        'invoice_number',
        'amount_cents',
        'description',
        'due_date',
    ];

    protected $guarded = ['id', 'created_at', 'updated_at', 'paid_at'];
}
```

---

## Sensitive Data Hiding

### Exclude from Serialization
Hide sensitive fields when serializing models:

```php
namespace Domain\Invoicing\Models;

class Invoice extends Model
{
    protected $hidden = [
        'payment_token',
        'internal_notes',
    ];

    protected $casts = [
        'stripe_customer_id' => 'encrypted:string',
    ];
}
```

### Secure DTO Responses
Use Laravel Data's `hidden` attribute:

```php
namespace Domain\Invoicing\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Hidden;

class InvoiceResource extends Data
{
    public function __construct(
        public int $id,
        public string $invoice_number,
        public float $amount,
        
        #[Hidden]
        public string $payment_gateway_id,
    ) {}
}
```

---

## Encryption

### Encrypt Sensitive Data
Use Laravel's encryption on sensitive domain attributes:

```php
namespace Domain\Payment\Models;

class PaymentMethod extends Model
{
    protected $casts = [
        'card_number' => 'encrypted',
        'cvv' => 'encrypted',
        'account_holder' => 'encrypted',
    ];
}
```

---

## CSRF Protection

### Protect Domain Actions
Ensure CSRF tokens on write operations:

```php
// In application-layer controller
namespace App\Modules\Invoicing\Controllers;

use Domain\Invoicing\Actions\CreateInvoice;

class InvoiceController
{
    public function store(Request $request)
    {
        $request->validate([
            'invoice_number' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        // CSRF automatically validated by middleware
        $invoice = app(CreateInvoice::class)->execute(
            $request->validated()
        );

        return response()->json($invoice);
    }
}
```

---

## Rate Limiting

### Limit Domain Actions
Use rate limiting on expensive operations:

```php
namespace Domain\Invoicing\Actions;

use Illuminate\Support\Facades\RateLimiter;

class SendInvoiceToCustomer
{
    public function execute(Invoice $invoice): void
    {
        $key = "send-invoice:{$invoice->id}";

        if (!RateLimiter::attempt($key, 5, 3600)) {
            throw new RateLimitExceededException(
                'Too many send attempts. Try again in 1 hour.'
            );
        }

        // Send logic...
    }
}
```

---

## Audit Logging

### Log Domain Changes
Track all domain modifications:

```php
namespace Domain\Invoicing\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected static function boot(): void
    {
        parent::boot();

        static::updated(function (self $invoice) {
            \Log::info('Invoice updated', [
                'invoice_id' => $invoice->id,
                'changes' => $invoice->getChanges(),
                'user_id' => auth()->id(),
            ]);
        });

        static::deleted(function (self $invoice) {
            \Log::warning('Invoice deleted', [
                'invoice_id' => $invoice->id,
                'user_id' => auth()->id(),
            ]);
        });
    }
}
```

Or use a dedicated audit package:
```bash
composer require spatie/laravel-activitylog
```

---

## Exception Handling

### Prevent Information Leakage
Don't expose internal details in exceptions:

```php
namespace Domain\Invoicing\Exceptions;

class InvoiceProcessingException extends \Exception
{
    public static function databaseError(\Exception $e): self
    {
        // Log the real error
        \Log::error('Invoice processing failed', ['error' => $e->getMessage()]);

        // Return generic exception to client
        return new self(
            'An error occurred while processing your invoice. Please try again.'
        );
    }
}
```

---

## Database Security

### Prepared Statements
Always use parameterized queries:

```php
namespace Domain\Invoicing\Actions;

use Illuminate\Support\Facades\DB;

class FetchInvoicesByPeriod
{
    public function execute(string $startDate, string $endDate): array
    {
        // ✅ Safe: parameterized
        return DB::select(
            'SELECT * FROM invoices WHERE created_at BETWEEN ? AND ?',
            [$startDate, $endDate]
        );

        // ❌ Unsafe: SQL injection risk
        // return DB::select("SELECT * FROM invoices WHERE created_at BETWEEN '$startDate' AND '$endDate'");
    }
}
```

---

## Dependencies & Vulnerabilities

### Keep Dependencies Updated
```bash
composer update
composer audit
```

Monitor security vulnerabilities:
```bash
composer require --dev sensiolabs/security-checker
composer security-check
```

---

## Key Takeaways

1. **Authorize at the domain level**, not just in controllers.
2. **Validate early** in value objects and DTOs.
3. **Sanitize all input** before entering the domain.
4. **Encrypt sensitive data** at rest and in transit.
5. **Log audit trails** for compliance and debugging.
6. **Hide sensitive fields** from serialized responses.
7. **Use prepared statements** to prevent SQL injection.
8. **Keep dependencies updated** and monitor vulnerabilities.
