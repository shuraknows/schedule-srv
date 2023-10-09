<?php

declare(strict_types=1);

namespace App\Domain\Schedule;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class ScheduleId
{
    private function __construct(private readonly UuidInterface $id)
    {
    }

    public static function create(): self
    {
        return new self(Uuid::uuid4());
    }

    public static function fromString(string $id): self
    {
        return new self(Uuid::fromString($id));
    }

    public function __toString(): string
    {
        return $this->id->toString();
    }
}
