<?php

declare(strict_types=1);

namespace App\Infrastructure\Eloquent;

use App\Domain\Common\DomainEventInterface;
use App\Domain\Schedule\Schedule as DomainSchedule;
use App\Domain\Schedule\ScheduleId;
use App\Domain\Schedule\ScheduleRepositoryInterface;
use App\Domain\Schedule\Segment as DomainSegment;
use App\Infrastructure\Eloquent\Models\Schedule;
use App\Infrastructure\Eloquent\Models\ScheduleMapper;
use App\Infrastructure\Eloquent\Models\Segment;
use App\Infrastructure\Eloquent\Models\SegmentMapper;
use App\Infrastructure\EventDispatcher;
use Illuminate\Support\Facades\DB;


final readonly class ScheduleRepository implements ScheduleRepositoryInterface
{
    public function __construct(private EventDispatcher $eventDispatcher)
    {
    }

    public function add(DomainSchedule $schedule): void
    {
        DB::transaction(function () use ($schedule): void {
            $model = Schedule::create(ScheduleMapper::domainModelToArray($schedule));
            $model->segments()->createMany(
                array_map(fn (DomainSegment $segment): array => SegmentMapper::domainModelToArray($segment), $schedule->segments())
            );

            $this->releaseEvents($schedule->releaseEvents());
        });
    }

    public function byId(ScheduleId $scheduleId): ?DomainSchedule
    {
        return Schedule::with('segments')->find($scheduleId->__toString())?->toDomainObject();
    }

    public function save(DomainSchedule $schedule): void
    {
        DB::transaction(function () use ($schedule): void {
            Schedule::query()->find($schedule->id()->__toString())->update(ScheduleMapper::domainModelToArray($schedule));

            foreach ($schedule->segments() as $segment) {
                Segment::find($segment->id())->update(SegmentMapper::domainModelToArray($segment));
            }

            $this->releaseEvents($schedule->releaseEvents());
        });
    }

    /**
     * @param DomainEventInterface[] $events
     */
    public function releaseEvents(array $events): void
    {
        foreach ($events as $event) {
            $this->eventDispatcher->dispatch($event);
        }
    }
}
