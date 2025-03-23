<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Habit extends Model
{
    /** @use HasFactory<\Database\Factories\HabitFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'name',  'days', 'reminder_time'];

    protected $hidden = ['user', 'created_at', 'updated_at','user_id'];

    public function getDays(): array
    {
        $map = [
            1 => 'Sunday',
            2 => 'Monday',
            4 => 'Tuesday',
            8 => 'Wednesday',
            16 => 'Thursday',
            32 => 'Friday',
            64 => 'Saturday',
        ];

        return array_values(array_filter($map, fn ($bit) => ($this->days & $bit) !== 0, ARRAY_FILTER_USE_KEY));
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function habit_logs(): HasMany
    {
        return $this->hasMany(HabitLog::class);
    }

    public function LongestStreak(): int
    {
        return $this->habit_logs()
            ->max('streak');
    }

    public function CompleteRate(): int
    {
        $user = $this->user;
        $today = now($user->timezone)->toDateString();
        $startDate = $this->created_at->toDateString();

        $counts = $this->habit_logs()
            ->whereBetween('date', [$startDate, $today])
            ->whereNotNull('status')
            ->selectRaw('
            COUNT(CASE WHEN status = 1 THEN 1 END) as completed,
            COUNT(*) as total
        ')->first();

        if (! $counts->total) {
            return 0;
        }

        return (int) (($counts->completed * 100) / $counts->total);

    }

    public function Easiness($completeRate = null): string
    {

        if (! $completeRate) {
            $completeRate = $this->CompleteRate();
        }

        return match (true) {
            $completeRate >= 75 => 'easy',
            $completeRate >= 50 => 'medium',
            $completeRate >= 25 => 'hard',
            default => 'very hard',
        };

    }

    public function CurrentStreak(): int
    {
        $user = $this->user;
        $todayLog = $this->habit_logs()->where('date', now($user->timezone)->format('Y-m-d'))->first();
        if (! $todayLog) {
            return 0;
        }

        return $todayLog->streak;
    }
}
