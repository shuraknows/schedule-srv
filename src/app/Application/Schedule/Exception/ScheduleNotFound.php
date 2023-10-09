<?php

declare(strict_types=1);

namespace App\Application\Schedule\Exception;

use App\Domain\Schedule\ScheduleId;
use Exception;

final class ScheduleNotFound extends Exception
{
    public function __construct(ScheduleId $id)
    {
        parent::__construct(sprintf('Schedule with id %s not found', $id->__toString()));
    }
}
