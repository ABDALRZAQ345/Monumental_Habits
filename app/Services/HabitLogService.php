<?php

namespace App\Services;

use App\Models\Habit;
use App\Models\HabitLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HabitLogService
{
    public function createHabitLogs(Habit $habit): void
    {
        $days = $habit->getDays();
        $user = $habit->user;
        $userTimezone = $user->timezone ?? 'UTC';

        $logs = [];
        $currentDate = now($userTimezone)->startOfYear();

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

    public function updateHabitLogs(Habit $habit): void
    {
        $user = $habit->user;
        $days = $habit->getDays();
        $currentDate = now($user->timezone)->toDateString();

        // update logs which are in selected days  but not checked
        // ? fix it  if will use postgres in production
        HabitLog::where('habit_id', $habit->id)
            ->where('date', '>=', $currentDate)
            ->whereIn(DB::raw("TO_CHAR(date, 'FMDay')"), $days)
            ->update(['status' => false]);

        HabitLog::where('habit_id', $habit->id)
            ->where('date', '>=', $currentDate)
            ->whereNotIn(DB::raw("TO_CHAR(date, 'FMDay')"), $days)
            ->update(['status' => null]);
    }

    public function UpdateHabitLogStatus($habitLog, bool $status): void
    {
        if ($status) {
            $this->updateStatusAndStreak($habitLog, $status);
            $this->updateNextLogsIfTrue($habitLog);
        } else {
            $this->updateNextLogsIfFalse($habitLog);
            $this->updateStatusAndStreak($habitLog, $status);
        }
    }

    public function updateStatusAndStreak($habitLog, bool $status): void
    {
        if ($status) {
            $previousLog = HabitLog::where('habit_id', $habitLog->habit_id)
                ->where('date', Carbon::parse($habitLog->date)->subDay())
                ->where('status', 1)
                ->first();
            $habitLog->update([
                'status' => true,
                'streak' => $previousLog ? $previousLog->streak + 1 : 1,
            ]);

        } else {
            $habitLog->update([
                'status' => false,
                'streak' => 0,
            ]);
        }

    }

    public function updateNextLogsIfFalse($habitLog): void
    {
        $streakValue = $habitLog->streak;
        $nextLogs = HabitLog::where('habit_id', $habitLog->habit_id)
            ->where('date', '>', $habitLog->date)
            ->where('status', 1)
            ->orderBy('date', 'asc')
            ->get();

        $previousDate = Carbon::parse($habitLog->date);
        if ($streakValue != 0) {
            foreach ($nextLogs as $log) {
                $logDate = Carbon::parse($log->date);

                if (! $logDate->isSameDay($previousDate->addDay())) {
                    break;
                }

                $log->streak -= $streakValue;
                $log->save();

                $previousDate = $logDate;
            }
        }
    }

    public function updateNextLogsIfTrue($habitLog): void
    {
        // updating the next streaks
        $nextLogs = HabitLog::where('habit_id', $habitLog->habit_id)
            ->where('date', '>', $habitLog->date)
            ->where('status', 1)
            ->orderBy('date', 'asc')
            ->get();

        $streakValue = $habitLog->streak;
        $previousDate = Carbon::parse($habitLog->date);

        foreach ($nextLogs as $log) {
            $logDate = Carbon::parse($log->date);

            if (! $logDate->isSameDay($previousDate->addDay())) {
                break;
            }

            $streakValue++;
            if ($log->streak == $streakValue) {
                break;
            }
            $log->streak = $streakValue;
            $log->save();

            $previousDate = $logDate;
        }
    }
}
