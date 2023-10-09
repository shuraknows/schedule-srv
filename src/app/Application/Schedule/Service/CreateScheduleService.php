<?php

declare(strict_types=1);

namespace App\Application\Schedule\Service;

use App\Application\Schedule\Dto\Assembler;
use App\Application\Schedule\Dto\CreateSchedule;
use App\Application\Schedule\Dto\ViewScheduleDto;
use App\Domain\Schedule\Calculators\SegmentCalculator;
use App\Domain\Schedule\Schedule;
use App\Domain\Schedule\ScheduleId;
use App\Domain\Schedule\ScheduleRepositoryInterface;

final readonly class CreateScheduleService
{
    public function __construct(
        private ScheduleRepositoryInterface $schedules,
        private SegmentCalculator $segmentCalculator,
        private Assembler $assembler
    ) {
    }

    public function execute(CreateSchedule $command): ViewScheduleDto
    {
        $schedule = Schedule::create(
            ScheduleId::create(),
            $this->segmentCalculator,
            $command->principalAmount(),
            $command->term(),
            $command->interestRate(),
            $command->euriborRate()
        );

        $this->schedules->add($schedule);

        return $this->assembler->toViewScheduleDto($schedule);
    }
}
