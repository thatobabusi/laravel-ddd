# Testing Your Domain

## Running Tests
```bash
composer test
```

## Test Structure
Tests should mirror your domain structure:
```
tests/
├─ Domain/
│  └─ Invoicing/
│     ├─ Models/InvoiceTest.php
│     ├─ Actions/SendInvoiceToCustomerTest.php
│     └─ ValueObjects/DollarAmountTest.php
└─ Feature/
   └─ InvoicingApiTest.php
```

## Testing Domain Objects

### Models
```php
class InvoiceTest extends TestCase
{
    #[Test]
    public function it_can_be_created()
    {
        $invoice = Invoice::factory()->create();
        $this->assertInstanceOf(Invoice::class, $invoice);
    }
}
```

### Actions
```php
class SendInvoiceTest extends TestCase
{
    #[Test]
    public function it_sends_invoice()
    {
        $invoice = Invoice::factory()->create();
        $action = new SendInvoiceToCustomer();
        $result = $action->execute($invoice);
        $this->assertTrue($result);
    }
}
```

### Value Objects
```php
class DollarAmountTest extends TestCase
{
    #[Test]
    public function it_represents_currency()
    {
        $amount = new DollarAmount(100);
        $this->assertEquals(100, $amount->value());
    }
}
```

## Best Practices
- Isolate domain tests from framework concerns.
- Use domain factories for test fixtures.
- Group related tests in test classes per domain object.
