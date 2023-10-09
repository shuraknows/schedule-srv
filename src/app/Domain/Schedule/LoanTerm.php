<?php

declare(strict_types=1);

namespace App\Domain\Schedule;

final readonly class LoanTerm
{
    public function __construct(private int $loanTerm)
    {
    }

    public function value(): int
    {
        return $this->loanTerm;
    }
}
