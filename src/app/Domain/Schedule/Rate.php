<?php

declare(strict_types=1);

namespace App\Domain\Schedule;

final readonly class Rate
{
    public function __construct(private float $rate)
    {
    }

    public function rate(): float
    {
        return $this->rate;
    }

    public function __toString(): string
    {
        return (string) $this->rate;
    }

    public function toInt(): int
    {
        return (int) $this->rate;
    }

    public function equals(self $rate): bool
    {
        return $this->rate === $rate->rate();
    }
}
