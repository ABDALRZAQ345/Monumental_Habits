<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Habit extends Model
{
    /** @use HasFactory<\Database\Factories\HabitFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'name',  'days', 'reminder_time', 'notifications_enabled'];

    protected $hidden = ['user', 'created_at', 'updated_at'];

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

    public function getLongestStreak() :int
    {
        return $this->habit_logs()
            ->max('streak');
    }
}
