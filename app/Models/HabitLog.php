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


    protected $fillable = ['date', 'status', 'habit_id','streak'];

    protected $hidden = ['created_at', 'updated_at', 'habit','streak','habit_id'];

    public function habit(): BelongsTo
    {
        return $this->belongsTo(Habit::class);
    }
    public function scopeOfWeek($query, $timezone = null)
    {
        $timezone = $timezone ?: config('app.timezone');
        $startOfWeek = Carbon::now($timezone)->startOfWeek();
        $endOfWeek   = Carbon::now($timezone)->endOfWeek();

        return $query->whereBetween('date', [$startOfWeek, $endOfWeek]);
    }

    public function scopeOfMonth($query, $month, $year)
    {
        $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        return $query->whereBetween('date', [$startOfMonth, $endOfMonth]);
    }

}
