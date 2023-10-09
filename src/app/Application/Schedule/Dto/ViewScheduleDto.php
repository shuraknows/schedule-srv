<?php

declare(strict_types=1);

namespace App\Application\Schedule\Dto;

final readonly class ViewScheduleDto
{
    public function __construct(
        public string $id,
        public int $amount,
        public int $term,
        public float $interestRate,
        /** @var ViewSegmentDto[] */
        public array $segments,
    ) {
    }
}
