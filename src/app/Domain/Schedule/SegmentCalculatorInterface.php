<?php

declare(strict_types=1);

namespace App\Domain\Schedule;

interface SegmentCalculatorInterface
{
    public function calculateSegments(
        Amount $principalAmount,
        LoanTerm $term,
        Rate $interest,
        Rate $euribor,
    ): array;
}
