<?php

declare(strict_types=1);

namespace App\Application\Schedule\Dto;

use App\Domain\Schedule\Schedule;
use App\Domain\Schedule\Segment;

final readonly class Assembler
{
    public function toViewScheduleDto(Schedule $schedule): ViewScheduleDto
    {
        return new ViewScheduleDto(
            $schedule->id()->__toString(),
            $schedule->amount()->amount(),
            $schedule->term()->value(),
            $schedule->interestRate()->rate(),
            array_map($this->toViewSegmentDto(...), $schedule->segments()),
        );
    }

    private function toViewSegmentDto(Segment $segment): ViewSegmentDto
    {
        return new ViewSegmentDto(
            $segment->sequenceNumber(),
            $segment->remainingAmount()->toInt(),
            $segment->principalPayment()->toInt(),
            $segment->interestPayment()->toInt(),
            $segment->euriborPayment()->toInt(),
            $segment->totalPayment()->toInt(),
            $segment->euriborRate()->toInt()
        );
    }
}
