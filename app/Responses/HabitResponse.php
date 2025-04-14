<?php

namespace App\Responses;

use App\Http\Resources\HabitResource;
use App\Models\Habit;
use App\Models\User;
use Carbon\Carbon;

class HabitResponse
{
    public static function response(User $user, Habit $habit): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => true,
            'user_current_date' => Carbon::now($user->timezone)->toDateString(),
            'habit' => habitResource::make($habit),
            'longest_streak' => $habit->LongestStreak(),
            'current_streak' => $habit->CurrentStreak(),
            'complete_rate' => $complete_rate = $habit->CompleteRate(),
            'easiness' => $habit->Easiness($complete_rate),
        ]);
    }
}
