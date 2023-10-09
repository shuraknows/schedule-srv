<?php

declare(strict_types=1);

namespace App\Domain\Schedule;

use App\Domain\Common\RootAggregate;
use App\Domain\Schedule\Calculators\MonthlyRateCalculator;
use App\Domain\Schedule\Calculators\SegmentCalculator;
use App\Domain\Schedule\Event\EuriborChanged;
use App\Domain\Schedule\Event\ScheduleCreated;

final class Schedule extends RootAggregate
{
    private array $segments = [];

    public static function create(
        ScheduleId $id,
        SegmentCalculator $segmentCalculator,
        Amount $amount,
        LoanTerm $term,
        Rate $interestRate,
        Rate $euriborRate,
    ): self {
        $schedule = new self($id, $amount, $term, $interestRate);
        $schedule->calculateSegments($segmentCalculator, $euriborRate);

        return $schedule;
    }

    private function __construct(
        private readonly ScheduleId $id,
        private readonly Amount $amount,
        private readonly LoanTerm $term,
        private readonly Rate $interestRate,
    ) {
        $this->writeEvent(new ScheduleCreated($id));
    }

    private function calculateSegments(SegmentCalculator $segmentCalculator, Rate $euriborRate): void
    {
        $this->segments = $segmentCalculator->calculateSegments(
            $this->amount,
            $this->term,
            $this->interestRate,
            $euriborRate,
        );
    }

    public function id(): ScheduleId
    {
        return $this->id;
    }

    public function amount(): Amount
    {
        return $this->amount;
    }

    public function term(): LoanTerm
    {
        return $this->term;
    }

    public function interestRate(): Rate
    {
        return $this->interestRate;
    }

    /**
     * @return Segment[]
     */
    public function segments(int $since = 0): array
    {
        return array_filter($this->segments, fn (Segment $segment) => $segment->sequenceNumber() >= $since);
    }

    public function changeEuriborRate(int $sinceSegment, Rate $euriborRate, MonthlyRateCalculator $monthlyRateCalculator): void
    {
        foreach ($this->segments($sinceSegment) as $segment) {

            if (!$segment->euriborRate()->equals($euriborRate)) {
                $segment->changeEuriborRate($euriborRate, $monthlyRateCalculator);
                $this->writeEvent(new EuriborChanged($this->id, $segment->sequenceNumber(), $euriborRate));
            }
        }
    }
}
