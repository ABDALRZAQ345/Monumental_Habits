<?php

namespace App\Services;

use App\Enums\Days;
use App\Models\Habit;
use App\Models\HabitLog;

class HabitLogService
{
    public function createHabitLogs(Habit $habit): void
    {
        $days = $habit->getDays();
        $user = $habit->user;
        $userTimezone = $user->timezone ?? 'UTC';

        $logs = [];
        $currentDate = now($userTimezone);

        for ($i = 0; $i < 366; $i++) {
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

    // /
    public function updateHabitLogs(Habit $habit): void
    {
        $user = $habit->user;
        $days = $habit->getDays();
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
}
