<?php

declare(strict_types=1);

namespace App\Application\Schedule\Dto;

final readonly class ViewSegmentDto
{
    public function __construct(
        public int $sequenceNumber,
        public int $remainingAmount,
        public int $principalPayment,
        public int $interestPayment,
        public int $euriborPayment,
        public int $totalPayment,
        public int $euriborRate,
    ) {
    }
}
