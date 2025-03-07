<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HabitLog extends Model
{
    /** @use HasFactory<\Database\Factories\HabitLogFactory> */
    use HasFactory;
    protected $fillable = ['date','status','habit_id'];
    public function habit(): BelongsTo
    {
        return $this->belongsTo(Habit::class);
    }
//    public function setDateAttribute($value): void
//    {
//        $userTimezone = auth()->user()->timezone ?? 'UTC';
//        $this->attributes['date'] = Carbon::parse($value, $userTimezone)->setTimezone('UTC');
//    }
//    public function getDateAttribute($value): Carbon
//    {
//        $userTimezone = auth()->user()->timezone ?? 'UTC';
//        return Carbon::parse($value, 'UTC')->setTimezone($userTimezone);
//    }
}
