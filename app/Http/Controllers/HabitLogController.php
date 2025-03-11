<?php

namespace App\Http\Controllers;

use App\Exceptions\BadRequestException;
use App\Exceptions\ServerErrorException;
use App\Models\Habit;
use App\Models\HabitLog;
use App\Services\HabitLogService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class HabitLogController extends Controller
{

    /**
     * @throws ServerErrorException
     */
    protected  HabitLogService  $habitLogService;
    public function __construct(HabitLogService $habitLogService)
    {
        $this->habitLogService = $habitLogService;
    }

    /**
     * @throws ServerErrorException
     * @throws AuthorizationException
     */
    public function update(Request $request, HabitLog $habitLog): JsonResponse
    {

        Gate::authorize('update-log', [$habit=$habitLog->habit,$habitLog]);
        $validated=$request->validate([
            'status' => ['required','boolean']
        ]);

        try {
           $user = \Auth::user();

           if($habitLog->status===null || ($habitLog->date > now($user->timezone)->format('Y-m-d')) ){
               $message =($habitLog->date > now($user->timezone)->format('Y-m-d')) ? "future day" : "day off";
               throw  new BadRequestException("you cant edit habit log for this day its " . $message);
           }
        $this->habitLogService->UpdateHabitLogStatus($habitLog,$validated['status']);
           return response()->json([
               'status'=> true,
               'message'=>'log updated successfully'
           ]);
        }
        catch (\Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }



    }
}
