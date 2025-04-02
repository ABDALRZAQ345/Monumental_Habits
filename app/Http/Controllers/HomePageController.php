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

            // todo might need to be cached
            $habits = $user->habits()->with(['habit_logs' => function ($query) use ($validated, $user) {
                $now = now()->format('Y-m-d');
                $query->ofWeek($user->timezone, (int) $validated['order'])->where('date', '<=', $now)->orderBy('date', 'desc');
            }])->select('id', 'name')->get();

            return response()->json([
                'user_current_date' => Carbon::now($user->timezone)->toDateString(),
                'habits' => habitResource::collection($habits),
            ]);
        } catch (\Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }

    }

    /**
     * @return float|int
     */
    public function CachingTime(?\App\Models\User $user, $order): int
    {
        $now = Carbon::now($user->timezone);
        $endOfDay = $now->copy()->endOfDay();
        $cacheExpiration = $now->diffInMinutes($endOfDay);
        if ($order != 0) {
            $cacheExpiration = 60 * 24 * 30 * 365;
        }

        return (int) $cacheExpiration;
    }
}
