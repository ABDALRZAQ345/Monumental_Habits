<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHabitRequest;
use App\Http\Resources\HabitResource;
use App\Services\HabitService;
use Illuminate\Http\JsonResponse;

class HabitController extends BaseController
{
    public function store(StoreHabitRequest $request): JsonResponse
    {
        $user=\Auth::user();

        $validated = $request->validated();

        $habit = HabitService::store($user,$validated);

        return response()->json([
            'status' => true,
            'message' => 'Habit created successfully',
            'habit' => habitResource::make($habit),
        ]);
    }
}
