<?php

declare(strict_types=1);

namespace App\Application\Schedule\Dto;

use App\Domain\Schedule\Rate;
use App\Domain\Schedule\ScheduleId;
use InvalidArgumentException;

final readonly class UpdateEuriborRate
{
    public function __construct(
        private string $scheduleId,
        private int $sinceSegment,
        private int $euriborRate,
    ) {
        $this->validate();
    }

    public function scheduleId(): ScheduleId
    {
        return ScheduleId::fromString($this->scheduleId);
    }

    public function sinceSegment(): int
    {
        return $this->sinceSegment;
    }

    public function euriborRate(): Rate
    {
        return new Rate($this->euriborRate);
    }

    private function validate(): void
    {
        if (!$this->sinceSegment || $this->sinceSegment <= 0) {
            throw new InvalidArgumentException('Invalid since segment');
        }

        if (!$this->euriborRate || $this->euriborRate <= 0) {
            throw new InvalidArgumentException('Invalid euribor rate');
        }
    }
}
