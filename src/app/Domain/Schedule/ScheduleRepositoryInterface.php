<?php

declare(strict_types=1);

namespace App\Domain\Schedule;

interface ScheduleRepositoryInterface
{
    public function add(Schedule $schedule): void;

    public function byId(ScheduleId $scheduleId): ?Schedule;

    public function save(Schedule $schedule): void;
}
