<?php

declare(strict_types=1);

namespace App\Infrastructure\Eloquent\Models;

use App\Domain\Schedule\Amount;
use App\Domain\Schedule\Rate;
use App\Domain\Schedule\Segment as DomainSegment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $sequence_number
 * @property int $remaining_amount
 * @property int $principal_payment
 * @property int $interest_payment,
 * @property int $euribor_payment,
 * @property int $total_payment,
 * @property int $euribor_rate,
 */
class Segment extends Model
{
    use HasFactory;

    protected $table = 'segments';

    protected $fillable = [
        'sequence_number',
        'remaining_amount',
        'principal_payment',
        'interest_payment',
        'euribor_payment',
        'total_payment',
        'euribor_rate',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    public function toDomainObject(): DomainSegment
    {
        $reflection = new \ReflectionClass(DomainSegment::class);
        $segment = $reflection->newInstanceWithoutConstructor();

        $reflection->getProperty('id')->setValue($segment, $this->id);
        $reflection->getProperty('sequenceNumber')->setValue($segment, $this->sequence_number);
        $reflection->getProperty('remainingAmount')->setValue($segment, new Amount($this->remaining_amount));
        $reflection->getProperty('principalPayment')->setValue($segment, new Amount($this->principal_payment));
        $reflection->getProperty('interestPayment')->setValue($segment, new Amount($this->interest_payment));
        $reflection->getProperty('euriborPayment')->setValue($segment, new Amount($this->euribor_payment));
        $reflection->getProperty('totalPayment')->setValue($segment, new Amount($this->total_payment));
        $reflection->getProperty('euriborRate')->setValue($segment, new Rate($this->euribor_rate));

        return $segment;
    }
}
