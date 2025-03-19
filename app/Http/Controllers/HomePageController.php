<?php

namespace App\Http\Controllers;

use App\Exceptions\ServerErrorException;
use App\Http\Requests\HomePageRequest;
use App\Http\Resources\HabitResource;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class HomePageController extends Controller
{
    /**
     * @throws ServerErrorException
     */
    public function __invoke(HomePageRequest $request): JsonResponse
    {
        try {
            $user = \Auth::user();
            $validated = $request->validated();

            $month = $validated['month'] ?? now($user->timezone)->month;
            $year = $validated['year'] ?? now($user->timezone)->year;

            $habits = $user->habits()->with(['habit_logs' => function ($query) use ($month, $year) {
                $query->ofMonth($month, $year)->where('date', '<=', now()->format('Y-m-d'))->orderBy('date', 'desc');
            }])->get()->makeHidden(['user_id', 'reminder_time', 'notifications_enabled']);

            return response()->json([
                'user_current_date' => Carbon::now($user->timezone)->toDateString(),
                'habits' => habitResource::collection($habits),
            ]);
        } catch (\Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }

    }
}
