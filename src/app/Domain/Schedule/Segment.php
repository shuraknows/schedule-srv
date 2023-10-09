<?php

declare(strict_types=1);

namespace App\Domain\Schedule;

use App\Domain\Schedule\Calculators\MonthlyRateCalculator;

final class Segment
{
    private readonly int $id;

    public function __construct(
        private readonly int $sequenceNumber,
        private readonly Amount $remainingAmount,
        private readonly Amount $principalPayment,
        private readonly Amount $interestPayment,
        private Amount $euriborPayment,
        private Amount $totalPayment,
        private Rate $euriborRate,
    ) {

    }

    public function id(): int
    {
        return $this->id;
    }

    public function sequenceNumber(): int
    {
        return $this->sequenceNumber;
    }

    public function remainingAmount(): Amount
    {
        return $this->remainingAmount;
    }

    public function principalPayment(): Amount
    {
        return $this->principalPayment;
    }

    public function interestPayment(): Amount
    {
        return $this->interestPayment;
    }

    public function euriborPayment(): Amount
    {
        return $this->euriborPayment;
    }

    public function totalPayment(): Amount
    {
        return $this->totalPayment;
    }

    public function euriborRate(): Rate
    {
        return $this->euriborRate;
    }

    public function changeEuriborRate(Rate $euriborRate, MonthlyRateCalculator $monthlyRateCalculator): void
    {
        $euriborMonthlyRate = $monthlyRateCalculator->calculate($euriborRate);
        $this->euriborPayment = $this->remainingAmount->applyRate($euriborMonthlyRate)->halfUp();
        $this->euriborRate = $euriborRate;
        $this->totalPayment = $this->principalPayment->add($this->interestPayment)->add($this->euriborPayment);
    }
}
