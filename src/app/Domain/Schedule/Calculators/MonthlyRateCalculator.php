<?php

declare(strict_types=1);

namespace App\Domain\Schedule\Calculators;

use App\Domain\Schedule\Rate;

final class MonthlyRateCalculator
{
    public function calculate(Rate $yearlyPercent): Rate
    {
        return new Rate($yearlyPercent->rate() / 100 / 12 / 100);
    }
}
