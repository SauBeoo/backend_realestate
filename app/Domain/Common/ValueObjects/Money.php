<?php

namespace App\Domain\Common\ValueObjects;

class Money
{
    /**
     * @param float $amount
     * @param string $currency
     */
    public function __construct(
        public readonly float $amount,
        public readonly string $currency = 'USD'
    ) {
        if ($amount < 0) {
            throw new \InvalidArgumentException('Amount cannot be negative');
        }
    }

    /**
     * Format the money with currency symbol.
     */
    public function format(): string
    {
        $symbols = [
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'VND' => '₫',
        ];

        $symbol = $symbols[$this->currency] ?? $this->currency . ' ';
        
        return $symbol . number_format($this->amount, 2);
    }

    /**
     * Add another Money object to this one.
     */
    public function add(Money $other): self
    {
        if ($this->currency !== $other->currency) {
            throw new \InvalidArgumentException('Cannot add money with different currencies');
        }
        
        return new self($this->amount + $other->amount, $this->currency);
    }

    /**
     * Subtract another Money object from this one.
     */
    public function subtract(Money $other): self
    {
        if ($this->currency !== $other->currency) {
            throw new \InvalidArgumentException('Cannot subtract money with different currencies');
        }
        
        $result = $this->amount - $other->amount;
        if ($result < 0) {
            throw new \InvalidArgumentException('Result would be negative');
        }
        
        return new self($result, $this->currency);
    }

    /**
     * Check if this Money object is equal to another.
     */
    public function equals(Money $other): bool
    {
        return $this->amount === $other->amount && $this->currency === $other->currency;
    }
} 