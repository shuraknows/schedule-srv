<?php

declare(strict_types=1);

namespace App\Domain\Common;

interface DomainEventInterface
{
    public function data(): array;
}
