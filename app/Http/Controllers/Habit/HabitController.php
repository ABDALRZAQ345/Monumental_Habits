<?php

namespace App\Http\Controllers\Habit;

use App\Exceptions\ServerErrorException;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Habit\StoreHabitRequest;
use App\Http\Requests\ShowHabitRequest;
use App\Http\Resources\HabitResource;
use App\Models\Habit;
use App\Responses\HabitResponse;
use App\Services\HabitService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class HabitController extends BaseController
{
    protected $habitService;
    public function __construct(HabitService $habitService)
    {
        $this->habitService = $habitService;
    }

    /**
     * @throws ServerErrorException
     */
    public function index(): JsonResponse
    {
        try {
            $user = \Auth::user();
            $habits = $user->habits()->select('id','name','reminder_time')->get();

            return response()->json([
                'status' => true,
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
            $validation =$this->habitService->CanCreateHabit($user, $validated['name']);
            if (!$validation['status']) {
                return response()->json($validation, 400);
            }
            HabitService::store($user, $validated);

            return response()->json([
                'status' => true,
                'message' => 'Habit created successfully',
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
    public function show(ShowHabitRequest $request, Habit $habit): JsonResponse
    {

        Gate::authorize('view', $habit);

        try {
            $user = \Auth::user();
            $validated = $request->validated();

            $month = $validated['month'] ?? now($user->timezone)->month;
            $year = $validated['year'] ?? now($user->timezone)->year;

            $habit->load(['habit_logs' => function ($query) use ($month, $year) {
                $query->ofMonth($month, $year);
            }]);

            return  HabitResponse::response($user,$habit);

        } catch (\Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }

    }
}
