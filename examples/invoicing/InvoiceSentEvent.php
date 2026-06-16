<?php

namespace Domain\Invoicing\Events;

use Domain\Invoicing\Models\Invoice;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * InvoiceSent Event
 *
 * Fired when an invoice is sent to the customer.
 */
class InvoiceSent
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly Invoice $invoice
    ) {}
}
