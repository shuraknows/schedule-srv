<?php

declare(strict_types=1);

namespace App\Infrastructure\Eloquent\Models;

use App\Domain\Schedule\Segment as DomainSegment;

final class SegmentMapper
{
    public static function domainModelToArray(DomainSegment $segment): array
    {
        return [
            'sequence_number' => $segment->sequenceNumber(),
            'remaining_amount' => $segment->remainingAmount()->amount(),
            'principal_payment' => $segment->principalPayment()->amount(),
            'interest_payment' => $segment->interestPayment()->amount(),
            'euribor_payment' => $segment->euriborPayment()->amount(),
            'total_payment' => $segment->totalPayment()->amount(),
            'euribor_rate' => $segment->euriborRate()->rate(),
        ];
    }
}
