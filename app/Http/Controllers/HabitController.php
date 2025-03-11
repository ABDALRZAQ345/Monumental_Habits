<?php

namespace App\Http\Controllers;

use App\Exceptions\ServerErrorException;
use App\Http\Requests\Habit\StoreHabitRequest;
use App\Http\Requests\ShowHabitRequest;
use App\Http\Resources\HabitResource;
use App\Models\Habit;
use App\Models\HabitLog;
use App\Services\HabitService;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;


class HabitController extends BaseController
{
    /**
     * @throws ServerErrorException
     */
    public function index(): JsonResponse
    {
        try {
            $user = \Auth::user();
            $habits = $user->habits;

            return response()->json([
                'status' => true,
                'message' => 'Habit created successfully',
                'habits' => HabitResource::collection($habits),
            ]);
        } catch (\Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }
    }

    /**
     * @throws ServerErrorException
     * @throws \Throwable
     */
    public function store(StoreHabitRequest $request): JsonResponse
    {
        $validated = $request->validated();
        try {

            $user = \Auth::user();
            $habit = HabitService::store($user, $validated);

            return response()->json([
                'status' => true,
                'message' => 'Habit created successfully',
                'habit' => habitResource::make($habit),
            ]);
        } catch (\Exception $e) {

            throw new ServerErrorException($e->getMessage());
        }

    }

    /**
     * @throws ServerErrorException
     * @throws \Throwable
     */
    public function update(StoreHabitRequest $request, Habit $habit): JsonResponse
    {
        Gate::authorize('update', $habit);
        $validated = $request->validated();
        try {

            $habit = HabitService::update($habit, $validated);

            return response()->json([
                'status' => true,
                'message' => 'Habit updated successfully',
                'habit' => habitResource::make($habit),
            ]);
        } catch (\Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }

    }

    /**
     * @throws ServerErrorException
     * @throws AuthorizationException
     */
    public function delete(Habit $habit): JsonResponse
    {
        Gate::authorize('delete', $habit);
        try {

            $habit->delete();

            return response()->json([
                'status' => true,
                'message' => 'Habit deleted successfully',
            ]);
        } catch (\Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }
    }

    /**
     * @throws ServerErrorException
     * @throws AuthorizationException
     */
    public function show(ShowHabitRequest $request,Habit $habit): JsonResponse
    {

        Gate::authorize('view', $habit);

        try {
            $user = \Auth::user();
            $validated=$request->validated();

            $month=$validated['month'] ?? now($user->timezone)->month;
            $year= $validated['year'] ?? now($user->timezone)->year;

            $habit->load(['habit_logs' => function ($query) use ($user,$month,$year) {
                $query->ofMonth($month,$year);
            }]);

            return response()->json([
                'status' => true,
                'habit' => habitResource::make($habit),
                'longest_streak' => $habit->LongestStreak(),
                'current_streak' => $habit->CurrentStreak(),
                'complete_rate' => $complete_rate =$habit->CompleteRate(),
                'easiness' => $habit->Easiness($complete_rate),
            ]);
        } catch (\Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }

    }
}
