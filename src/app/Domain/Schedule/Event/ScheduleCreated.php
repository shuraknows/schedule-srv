<?php

declare(strict_types=1);

namespace App\Domain\Schedule\Event;

use App\Domain\Common\DomainEventInterface;
use App\Domain\Schedule\ScheduleId;

final readonly class ScheduleCreated implements DomainEventInterface
{
    public function __construct(private ScheduleId $id)
    {

    }

    public function data(): array
    {
        return [
            'id' => $this->id->__toString(),
        ];
    }
}
