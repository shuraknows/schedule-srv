<?php

declare(strict_types=1);

namespace App\Infrastructure\Eloquent\Models;

use App\Domain\Schedule\Schedule as DomainSchedule;

final class ScheduleMapper
{
    public static function domainModelToArray(DomainSchedule $schedule): array
    {
        return [
            'id' => $schedule->id()->__toString(),
            'amount' => $schedule->amount()->__toString(),
            'term' => $schedule->term()->value(),
            'interest_rate' => $schedule->interestRate()->__toString(),
        ];
    }
}
