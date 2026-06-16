<?php

namespace Domain\Payment\Customer\Actions;

use Domain\Payment\Customer\Exceptions\InsufficientFundsException;
use Domain\Payment\Internal\Actions\RecordPayment;
use Domain\Payment\Enums\PaymentStatus;
use Domain\Payment\Events\PaymentProcessed;

/**
 * SubmitPayment Action (Customer Subdomain)
 *
 * Customer-facing action to submit a payment.
 * Delegates to internal RecordPayment for persistence.
 */
class SubmitPayment
{
    public function __construct(
        private RecordPayment $recordPayment
    ) {}

    /**
     * Execute payment submission
     */
    public function execute(
        int $customerId,
        int $amountCents,
        string $paymentMethod
    ): void {
        // Validate funds (business rule)
        if ($this->hasInsufficientFunds($customerId, $amountCents)) {
            throw new InsufficientFundsException(
                'Insufficient funds for this payment.'
            );
        }

        // Delegate to internal action
        $payment = $this->recordPayment->execute(
            customerId: $customerId,
            amountCents: $amountCents,
            paymentMethod: $paymentMethod,
            status: PaymentStatus::Processing,
        );

        // Dispatch shared event
        event(new PaymentProcessed($payment));
    }

    /**
     * Check available funds (simplified)
     */
    private function hasInsufficientFunds(int $customerId, int $amount): bool
    {
        // In real app: check customer balance / payment method
        return false;
    }
}
