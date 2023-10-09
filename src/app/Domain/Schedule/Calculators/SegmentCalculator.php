<?php

declare(strict_types=1);

namespace App\Domain\Schedule\Calculators;

use App\Domain\Schedule\Amount;
use App\Domain\Schedule\LoanTerm;
use App\Domain\Schedule\Rate;
use App\Domain\Schedule\Segment;
use App\Domain\Schedule\SegmentCalculatorInterface;

final readonly class SegmentCalculator implements SegmentCalculatorInterface
{
    public function __construct(
        private MonthlyRateCalculator $monthlyRateCalculator,
        private AnnuityCalculator $annuityCalculator,
    ) {
    }

    public function calculateSegments(Amount $principalAmount, LoanTerm $term, Rate $interest, Rate $euribor): array
    {
        $segments = [];
        $interestMonthlyRate = $this->monthlyRateCalculator->calculate($interest);
        $euriborMonthlyRate = $this->monthlyRateCalculator->calculate($euribor);
        $interestAndPrincipal = $this->annuityCalculator->calculate($principalAmount, $interestMonthlyRate, $term);
        $remainingAmount = $principalAmount;

        for ($segmentNumber = 1; $segmentNumber <= $term->value(); $segmentNumber++) {
            $interestPayment = $remainingAmount->applyRate($interestMonthlyRate)->halfDown();
            $euriborPayment = $remainingAmount->applyRate($euriborMonthlyRate)->halfUp();
            $principalPayment = $interestAndPrincipal->subtract($interestPayment)->min($remainingAmount);

            $segments[] = new Segment(
                $segmentNumber,
                $remainingAmount,
                $principalPayment,
                $interestPayment,
                $euriborPayment,
                $principalPayment->add($interestPayment)->add($euriborPayment),
                $euribor,
            );

            $remainingAmount = $remainingAmount->subtract($principalPayment);
        }

        return $segments;
    }
}
