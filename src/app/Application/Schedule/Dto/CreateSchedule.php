<?php

declare(strict_types=1);

namespace App\Application\Schedule\Dto;

use App\Domain\Schedule\Amount;
use App\Domain\Schedule\LoanTerm;
use App\Domain\Schedule\Rate;
use InvalidArgumentException;

final readonly class CreateSchedule
{
    public function __construct(
        private int $principalAmount,
        private int $term,
        private int $interestRate,
        private int $euriborRate,
    ) {
        $this->validate();
    }

    public function principalAmount(): Amount
    {
        return new Amount($this->principalAmount);
    }

    public function term(): LoanTerm
    {
        return new LoanTerm($this->term);
    }

    public function interestRate(): Rate
    {
        return new Rate($this->interestRate);
    }

    public function euriborRate(): Rate
    {
        return new Rate($this->euriborRate);
    }

    private function validate(): void
    {
        if (!$this->principalAmount || $this->principalAmount < 0) {
            throw new InvalidArgumentException('Invalid principal amount');
        }

        if (!$this->term || $this->term < 0) {
            throw new InvalidArgumentException('Invalid term');
        }

        if (!$this->interestRate || $this->interestRate < 0) {
            throw new InvalidArgumentException('Invalid interest rate');
        }

        if (!$this->euriborRate || $this->euriborRate < 0) {
            throw new InvalidArgumentException('Invalid euribor rate');
        }
    }
}
