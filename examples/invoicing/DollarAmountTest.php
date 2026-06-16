<?php

namespace Tests\Domain\Invoicing\ValueObjects;

use PHPUnit\Framework\TestCase;
use Domain\Invoicing\ValueObjects\DollarAmount;

/**
 * DollarAmount Value Object Tests
 *
 * Validates immutability, arithmetic operations, and formatting.
 */
class DollarAmountTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_be_created_from_cents(): void
    {
        $amount = new DollarAmount(9999);
        $this->assertEquals(9999, $amount->cents());
        $this->assertEquals(99.99, $amount->toDollars());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_be_created_from_dollars(): void
    {
        $amount = DollarAmount::fromDollars(99.99);
        $this->assertEquals(9999, $amount->cents());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_rejects_negative_amounts(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new DollarAmount(-100);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_formats_for_display(): void
    {
        $amount = DollarAmount::fromDollars(50);
        $this->assertEquals('$50.00', $amount->formatted('USD'));
        $this->assertEquals('€50.00', $amount->formatted('EUR'));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_compares_amounts(): void
    {
        $amount1 = DollarAmount::fromDollars(50);
        $amount2 = DollarAmount::fromDollars(50);
        $amount3 = DollarAmount::fromDollars(100);

        $this->assertTrue($amount1->equals($amount2));
        $this->assertFalse($amount1->equals($amount3));
        $this->assertTrue($amount1->lessThan($amount3));
        $this->assertTrue($amount3->greaterThan($amount1));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_performs_arithmetic(): void
    {
        $amount1 = DollarAmount::fromDollars(50);
        $amount2 = DollarAmount::fromDollars(30);

        $sum = $amount1->add($amount2);
        $this->assertEquals(8000, $sum->cents());

        $diff = $amount1->subtract($amount2);
        $this->assertEquals(2000, $diff->cents());

        $multiplied = $amount1->multiply(2);
        $this->assertEquals(10000, $multiplied->cents());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_is_immutable(): void
    {
        $amount = DollarAmount::fromDollars(100);
        $result = $amount->add(DollarAmount::fromDollars(50));

        // Original unchanged
        $this->assertEquals(10000, $amount->cents());
        // New object created
        $this->assertEquals(15000, $result->cents());
    }
}
