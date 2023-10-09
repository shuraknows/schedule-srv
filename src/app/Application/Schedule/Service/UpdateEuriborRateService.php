<?php

declare(strict_types=1);

namespace App\Application\Schedule\Service;

use App\Application\Schedule\Dto\Assembler;
use App\Application\Schedule\Dto\UpdateEuriborRate;
use App\Application\Schedule\Dto\ViewScheduleDto;
use App\Application\Schedule\Exception\ScheduleNotFound;
use App\Domain\Schedule\Calculators\MonthlyRateCalculator;
use App\Domain\Schedule\ScheduleRepositoryInterface;

final readonly class UpdateEuriborRateService
{
    public function __construct(
        private Assembler $assembler,
        private ScheduleRepositoryInterface $schedules,
        private MonthlyRateCalculator $monthlyRateCalculator,
    ) {
    }

    /**
     * @throws ScheduleNotFound
     */
    public function execute(UpdateEuriborRate $command): ViewScheduleDto
    {
        $schedule = $this->schedules->byId($command->scheduleId()) ?? throw new ScheduleNotFound($command->scheduleId());
        $schedule->changeEuriborRate($command->sinceSegment(), $command->euriborRate(), $this->monthlyRateCalculator);
        $this->schedules->save($schedule);

        return $this->assembler->toViewScheduleDto($schedule);
    }
}
