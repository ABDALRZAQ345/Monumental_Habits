<?php

namespace App\Responses;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;

class UserProfileResponse
{
    public static function response(User $user, UserService $userService): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => true,
            'user' => UserResource::make($user),
            'achievements' => 0, // todo coming soon
            'longest_streak' => $user->habits()->get()->map->LongestStreak()->max() ?? 0,
            'habits_completion' => $userService->ComplementInAWeek($user),
        ]);
    }
}
