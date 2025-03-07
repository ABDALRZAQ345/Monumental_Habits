<?php

namespace App\Observers;

use App\Models\Habit;
use App\Services\HabitLogService;

class HabitObserver
{
    /**
     * Handle the Habit "created" event.
     */
    protected HabitLogService $habitLogService;

    public function __construct(HabitLogService $habitLogService)
    {
        $this->habitLogService = $habitLogService;
    }

    public function created(Habit $habit): void
    {

        $this->habitLogService->createHabitLogs($habit);
    }

    /**
     * Handle the Habit "updated" event.
     */
    public function updated(Habit $habit): void
    {

        $this->habitLogService->updateHabitLogs($habit);

    }

    /**
     * Handle the Habit "deleted" event.
     */
    public function deleted(Habit $habit): void
    {
        //
    }

    /**
     * Handle the Habit "restored" event.
     */
    public function restored(Habit $habit): void
    {
        //
    }

    /**
     * Handle the Habit "force deleted" event.
     */
    public function forceDeleted(Habit $habit): void
    {
        //
    }
}
