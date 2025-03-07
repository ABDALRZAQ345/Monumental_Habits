<?php

namespace App\Console\Commands;

use App\Models\Habit;
use App\Services\HabitLogService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateHabitLogs extends Command
{
    protected $signature = 'habits:generate-logs';

    protected $description = 'Generate a new habit log entry for each habit every day';

    /**
     * @throws \Throwable
     */
    public function handle(HabitLogService $habitLogService): void
    {
        try {
            DB::beginTransaction();
            $habits = Habit::all();
            DB::table('habit_logs')->delete();
            foreach ($habits as $habit) {

                $habitLogService->createHabitLogs($habit);
            }
            DB::commit();

        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }

    }
}
