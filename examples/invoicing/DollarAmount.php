<?php

namespace Domain\Invoicing\ValueObjects;

/**
 * DollarAmount Value Object
 *
 * Represents a monetary amount in cents to avoid floating-point precision issues.
 * Immutable and type-safe.
 */
readonly class DollarAmount
{
    public function __construct(private int $cents)
    {
        if ($cents < 0) {
            throw new \InvalidArgumentException('Amount cannot be negative.');
        }
    }

    /**
     * Create from dollars (e.g., 99.99)
     */
    public static function fromDollars(int|float $dollars): self
    {
        return new self((int) round($dollars * 100));
    }

    /**
     * Get amount in cents
     */
    public function cents(): int
    {
        return $this->cents;
    }

    /**
     * Get amount in dollars
     */
    public function toDollars(): float
    {
        return $this->cents / 100;
    }

    /**
     * Format for display
     */
    public function formatted(string $currency = 'USD'): string
    {
        $symbol = match ($currency) {
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'ZAR' => 'R',
            default => $currency,
        };

        return sprintf('%s%.2f', $symbol, $this->toDollars());
    }

    /**
     * Compare amounts
     */
    public function equals(self $other): bool
    {
        return $this->cents === $other->cents;
    }

    /**
     * Check if greater than
     */
    public function greaterThan(self $other): bool
    {
        return $this->cents > $other->cents;
    }

    /**
     * Check if less than
     */
    public function lessThan(self $other): bool
    {
        return $this->cents < $other->cents;
    }

    /**
     * Add amounts
     */
    public function add(self $other): self
    {
        return new self($this->cents + $other->cents);
    }

    /**
     * Subtract amounts
     */
    public function subtract(self $other): self
    {
        return new self($this->cents - $other->cents);
    }

    /**
     * Multiply by scalar
     */
    public function multiply(int|float $multiplier): self
    {
        return new self((int) round($this->cents * $multiplier));
    }
}
