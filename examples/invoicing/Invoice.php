<?php

namespace Domain\Invoicing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Domain\Invoicing\ValueObjects\DollarAmount;

/**
 * Invoice Model
 *
 * Core domain model representing an invoice.
 * Encapsulates invoice state and basic operations.
 */
class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    protected $fillable = [
        'customer_id',
        'invoice_number',
        'amount_cents',
        'description',
        'due_date',
        'sent_at',
        'paid_at',
    ];

    protected $casts = [
        'amount_cents' => 'integer',
        'sent_at' => 'datetime',
        'paid_at' => 'datetime',
        'due_date' => 'datetime',
    ];

    /**
     * Cast amount_cents to DollarAmount value object
     */
    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn () => new DollarAmount($this->amount_cents),
            set: fn (DollarAmount $value) => $value->cents(),
        );
    }

    /**
     * Check if invoice has been sent
     */
    public function isSent(): bool
    {
        return $this->sent_at !== null;
    }

    /**
     * Check if invoice has been paid
     */
    public function isPaid(): bool
    {
        return $this->paid_at !== null;
    }

    /**
     * Check if invoice is overdue
     */
    public function isOverdue(): bool
    {
        return !$this->isPaid() && now()->isAfter($this->due_date);
    }

    /**
     * Mark invoice as sent
     */
    public function markAsSent(): void
    {
        $this->update(['sent_at' => now()]);
    }

    /**
     * Mark invoice as paid
     */
    public function markAsPaid(): void
    {
        $this->update(['paid_at' => now()]);
    }

    /**
     * Get days until due
     */
    public function daysUntilDue(): int
    {
        return now()->diffInDays($this->due_date);
    }

    /**
     * Check if can be deleted (draft only)
     */
    public function canBeDeleted(): bool
    {
        return !$this->isSent() && !$this->isPaid();
    }
}
