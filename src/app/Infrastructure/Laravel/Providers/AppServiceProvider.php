<?php

declare(strict_types=1);

namespace App\Infrastructure\Laravel\Providers;

use App\Domain\Schedule\ScheduleRepositoryInterface;
use App\Infrastructure\Eloquent\ScheduleRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            ScheduleRepositoryInterface::class,
            ScheduleRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
