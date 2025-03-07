<?php

namespace App\Http\Controllers;

use App\Exceptions\ServerErrorException;
use App\Http\Requests\StoreHabitRequest;
use App\Http\Resources\HabitResource;
use App\Models\Habit;
use App\Services\HabitLogService;
use App\Services\HabitService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class HabitController extends BaseController
{


    /**
     * @throws ServerErrorException
     */
    public function index(): JsonResponse
    {
        try {
            $user=\Auth::user();
            $habits= $user->habits;
            return response()->json([
                'status' => true,
                'message' => 'Habit created successfully',
                'habits' => HabitResource::collection($habits)
            ]);
        }
        catch (\Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }
    }

    /**
     * @throws ServerErrorException
     * @throws \Throwable
     */
    public function store(StoreHabitRequest $request): JsonResponse
    {
        try {
            db::beginTransaction();
            $user=\Auth::user();
            $validated = $request->validated();
            $habit = HabitService::store($user,$validated);

            db::commit();
            return response()->json([
                'status' => true,
                'message' => 'Habit created successfully',
                'habit' => habitResource::make($habit),
            ]);
        }
        catch (\Exception $e) {
            db::rollBack();
            throw new ServerErrorException($e->getMessage());
        }

    }

    /**
     * @throws ServerErrorException
     * @throws \Throwable
     */
    public function update(StoreHabitRequest $request, Habit $habit): JsonResponse
    {
        $validated = $request->validated();
        try {
            db::beginTransaction();
            $user=\Auth::user();
            $habit=$user->habits()->findOrFail($habit->id);
            $habit=HabitService::update($habit,$validated);

            db::commit();
            return response()->json([
                'status' => true,
                'message' => 'Habit updated successfully',
                'habit' => habitResource::make($habit),
            ]);
        }
        catch (\Exception $e) {
            db::rollBack();
            throw new ServerErrorException($e->getMessage());
        }

    }

    /**
     * @throws ServerErrorException
     */
    public function delete(Habit $habit): JsonResponse
    {
        try {
            $user=\Auth::user();
            $habit=$user->habits()->findOrFail($habit->id);
            $habit->delete();
            return response()->json([
                'status' => true,
               'message' => 'Habit deleted successfully',
            ]);
        }
        catch (\Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function show(Habit $habit): JsonResponse
    {
        try {
            $user=\Auth::user();
            $user->habits()->findOrFail($habit->id);

            $longest_streak=0;//todo
            $current_streak=0;//todo
            $complete_rate=0; // todo
            $easiness = 0 ; // todo
            /*
             * 0 -25 hard
             * 25 -50 midium
             * 50 -75 easy
             * 75 -100 very easy
             */
            return response()->json([
                'status' => true,
                'habit' => habitResource::make($habit),
                'longest_streak' => $longest_streak,
                'current_streak' => $current_streak,
                'complete_rate' => $complete_rate,
                'easiness' => $easiness,
            ]);
        }
        catch (\Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }

    }
}
