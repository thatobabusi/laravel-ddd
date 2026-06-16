<?php

namespace Domain\Invoicing\Actions;

use Domain\Invoicing\Models\Invoice;
use Domain\Invoicing\ValueObjects\DollarAmount;
use Domain\Invoicing\Events\InvoiceCreated;
use Illuminate\Database\DatabaseManager;

/**
 * CreateInvoice Action
 *
 * Encapsulates the business logic for creating a new invoice.
 * Ensures transactional integrity and dispatches domain events.
 */
class CreateInvoice
{
    public function __construct(
        private DatabaseManager $db
    ) {}

    /**
     * Execute action
     *
     * @param array $data {
     *     @var int $customer_id
     *     @var string $invoice_number
     *     @var DollarAmount $amount
     *     @var string $description
     *     @var \Carbon\CarbonImmutable $due_date
     * }
     */
    public function execute(array $data): Invoice
    {
        return $this->db->transaction(function () use ($data) {
            $this->validateData($data);

            $invoice = Invoice::create([
                'customer_id' => $data['customer_id'],
                'invoice_number' => $data['invoice_number'],
                'amount_cents' => $data['amount']->cents(),
                'description' => $data['description'],
                'due_date' => $data['due_date'],
            ]);

            // Dispatch event after transaction commits
            event(new InvoiceCreated($invoice));

            return $invoice;
        });
    }

    /**
     * Validate input data
     */
    private function validateData(array $data): void
    {
        if (empty($data['customer_id']) || !is_int($data['customer_id'])) {
            throw new \InvalidArgumentException('customer_id must be a valid integer.');
        }

        if (empty($data['invoice_number'])) {
            throw new \InvalidArgumentException('invoice_number is required.');
        }

        if (!isset($data['amount']) || !$data['amount'] instanceof DollarAmount) {
            throw new \InvalidArgumentException('amount must be a DollarAmount instance.');
        }

        if ($data['amount']->cents() <= 0) {
            throw new \InvalidArgumentException('Invoice amount must be greater than zero.');
        }

        if (Invoice::where('invoice_number', $data['invoice_number'])->exists()) {
            throw new \DomainException('Invoice number already exists.');
        }
    }
}
