<?php

declare(strict_types=1);

namespace App\Domain\Schedule;

final class Amount
{
    public function __construct(private readonly float $amount)
    {
    }

    public function amount(): int
    {
        return (int) $this->amount;
    }

    public function add(self $amount): self
    {
        return new self($this->amount + $amount->amount());
    }

    public function subtract(self $amount): self
    {
        return new self($this->amount - $amount->amount());
    }

    public function applyRate(Rate $rate): self
    {
        return new self($this->amount * $rate->rate());
    }

    public function divide(float $divisor): self
    {
        return new self($this->amount / $divisor);
    }

    public function halfUp(): self
    {
        return new self(round($this->amount));
    }

    public function halfDown(): self
    {
        return new self(round($this->amount, 0, PHP_ROUND_HALF_DOWN));
    }

    public function min(self $amount): self
    {
        return new self(min($this->amount, $amount->amount()));
    }

    public function __toString(): string
    {
        return (string) $this->amount;
    }

    public function toInt(): int
    {
        return (int) $this->amount;
    }
}
