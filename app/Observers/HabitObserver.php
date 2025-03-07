<?php

namespace App\Observers;

use App\Models\Habit;
use App\Models\HabitLog;
use Illuminate\Support\Facades\Auth;

class HabitObserver
{
    /**
     * Handle the Habit "created" event.
     */
    public function created(Habit $habit): void
    {
        $user = Auth::user();
        if (!$user) {
            // if it created by seeder of tinker for example
            return;
        }

        $days = $habit->getDays();
        $this->createHabitLogs($habit, $days);
    }
    private function createHabitLogs(Habit $habit, array $days): void
    {
        $user = Auth::user();
        $userTimezone = $user->timezone ?? 'UTC';

        $logs = [];
        $currentDate = now($userTimezone);

        for ($i = 0; $i < 32; $i++) {
            $date = $currentDate->copy()->addDays($i)->toDateString();
            $dayName = $currentDate->copy()->addDays($i)->format('l');

            $status = in_array($dayName, $days) ? false : null;

            $logs[] = [
                'habit_id' => $habit->id,
                'date' => $date,
                'status' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        HabitLog::insert($logs);
    }
    /**
     * Handle the Habit "updated" event.
     */
    public function updated(Habit $habit): void
    {
        $user = Auth::user();
        if (!$user) {
            // if it created by seeder of tinker for example
            return;
        }

        $days = $habit->getDays();
        $this->updateHabitLogs($habit, $days);

    }
    public function updateHabitLogs(Habit $habit, array $days): void
    {
        $user = Auth::user();

        $currentDate = now($user->timezone)->toDateString();



     // update logs which are in selected days  but not checked
        HabitLog::where('habit_id', $habit->id)
            ->where('date', '>=', $currentDate)
            ->whereIn(\DB::raw('DAYNAME(date)'), $days)
            ->update(['status' => false]);
       // update logs which are not selected
        HabitLog::where('habit_id', $habit->id)
            ->where('date', '>=', $currentDate)
            ->whereNotIn(\DB::raw('DAYNAME(date)'), $days)
            ->update(['status' => null]);
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
