<?php

declare(strict_types=1);

namespace App\Infrastructure\Eloquent\Models;

use App\Domain\Schedule\Amount;
use App\Domain\Schedule\LoanTerm;
use App\Domain\Schedule\Rate;
use App\Domain\Schedule\Schedule as DomainSchedule;
use App\Domain\Schedule\ScheduleId;
use App\Domain\Schedule\Segment as DomainSegment;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property int $amount
 * @property int $term
 * @property int $interest_rate
 */
class Schedule extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'schedules';
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'amount',
        'term',
        'interest_rate',
    ];

    public function toDomainObject(): DomainSchedule
    {
        $reflection = new \ReflectionClass(DomainSchedule::class);
        $schedule = $reflection->newInstanceWithoutConstructor();

        $reflection->getProperty('id')->setValue($schedule, ScheduleId::fromString($this->id));
        $reflection->getProperty('amount')->setValue($schedule, new Amount($this->amount));
        $reflection->getProperty('term')->setValue($schedule, new LoanTerm($this->term));
        $reflection->getProperty('interestRate')->setValue($schedule, new Rate($this->interest_rate));

        $segments = array_map(
            fn (Segment $segment): DomainSegment => $segment->toDomainObject(),
            $this->segments()->get()->all()
        );

        // ensure segments are sorted by sequence number
        usort($segments, fn (DomainSegment $left, DomainSegment $right) => $left->sequenceNumber() <=> $right->sequenceNumber());

        $reflection->getProperty('segments')->setValue($schedule, $segments);

        return $schedule;
    }

    public function segments(): HasMany
    {
        return $this->hasMany(Segment::class, 'schedule_id');
    }
}
