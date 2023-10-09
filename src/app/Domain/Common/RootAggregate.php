<?php

declare(strict_types=1);

namespace App\Domain\Common;

abstract class RootAggregate
{
    private array $events = [];

    public function writeEvent(DomainEventInterface $event): void
    {
        $this->events[] = $event;
    }

    public function releaseEvents(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }
}
