<?php

namespace App\Http\Controllers;

use App\Exceptions\ServerErrorException;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomePageController extends Controller
{
    /**
     * @throws ServerErrorException
     */
    public function __invoke()
    {
        try {
            $user = \Auth::user();
            $habits = $user->habits()->with(['habit_logs' => function ($query) use ($user) {
                $query->ofWeek($user->timezone);
            }])->get();
            return response()->json([
                "user_current_date" => Carbon::now($user->timezone)->toDateString(),
                'habits' =>$habits
            ]);
        }
        catch (\Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }

    }
}
