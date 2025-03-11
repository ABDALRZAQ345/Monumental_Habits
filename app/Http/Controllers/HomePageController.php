<?php

namespace App\Http\Controllers;

use App\Exceptions\ServerErrorException;
use App\Http\Requests\HomePageRequest;
use App\Http\Resources\HabitResource;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class HomePageController extends Controller
{
    /**
     * @throws ServerErrorException
     */
    public function __invoke(HomePageRequest $request): JsonResponse
    {
        try {
            $user = \Auth::user();
            $validated=$request->validated();

            $month=$validated['month'] ?? now($user->timezone)->month;
            $year= $validated['year'] ?? now($user->timezone)->year;

            $habits = $user->habits()->with(['habit_logs' => function ($query) use ($month, $year, $user) {
                $query->ofMonth($month,$year)->where('date','<=',now()->format('Y-m-d'))->orderBy('date','desc');
            }])->get()->makeHidden(['user_id','reminder_time','notifications_enabled']);
            return response()->json([
                "user_current_date" => Carbon::now($user->timezone)->toDateString(),
                'habits' => habitResource::collection($habits),
            ]);
        }
        catch (\Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }

    }
}
