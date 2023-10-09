<?php

declare(strict_types=1);

namespace App\Domain\Schedule\Calculators;

use App\Domain\Schedule\Amount;
use App\Domain\Schedule\LoanTerm;
use App\Domain\Schedule\Rate;

final class AnnuityCalculator
{
    public function calculate(Amount $principalAmount, Rate $monthlyRate, LoanTerm $term): Amount
    {
        $annuityCoefficient = $this->annuityCoefficient($monthlyRate, $term);

        return $principalAmount->applyRate($annuityCoefficient)->halfDown();
    }

    private function annuityCoefficient(Rate $monthlyRate, LoanTerm $term): Rate
    {
        return new Rate(
            $monthlyRate->rate() + (
                $monthlyRate->rate() / (pow(1 + $monthlyRate->rate(), $term->value()) - 1)
            )
        );
    }
}
