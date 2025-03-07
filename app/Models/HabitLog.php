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

    protected $fillable = ['date', 'status', 'habit_id'];

    protected $hidden = ['created_at', 'updated_at', 'habit'];

    public function habit(): BelongsTo
    {
        return $this->belongsTo(Habit::class);
    }

}
