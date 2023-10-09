<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Domain\Common\DomainEventInterface;
use Illuminate\Support\Facades\Log;

final class EventDispatcher
{
    public function dispatch(DomainEventInterface $event): void
    {
        Log::channel('eventlog')
            ->info(
                sprintf('Dispatching event: %s . ', get_class($event)),
                $event->data()
            );
    }
}
