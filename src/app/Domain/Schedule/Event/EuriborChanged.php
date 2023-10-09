<?php

declare(strict_types=1);

namespace App\Domain\Schedule\Event;

use App\Domain\Common\DomainEventInterface;
use App\Domain\Schedule\Rate;
use App\Domain\Schedule\ScheduleId;

final readonly class EuriborChanged implements DomainEventInterface
{
    public function __construct(
        private ScheduleId $id,
        private int $segmentNumber,
        private Rate $euriborRate
    ) {

    }

    public function data(): array
    {
        return [
            'id' => $this->id->__toString(),
            'segmentNumber' => $this->segmentNumber,
            'euriborRate' => $this->euriborRate->toInt(),
        ];
    }
}
