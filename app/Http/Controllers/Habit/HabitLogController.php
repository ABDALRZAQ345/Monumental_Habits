<?php

namespace App\Http\Controllers\Habit;

use App\Exceptions\BadRequestException;
use App\Exceptions\ServerErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateStatusRequest;
use App\Models\HabitLog;
use App\Services\HabitLogService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class HabitLogController extends Controller
{
    /**
     * @throws ServerErrorException
     */
    protected HabitLogService $habitLogService;

    public function __construct(HabitLogService $habitLogService)
    {
        $this->habitLogService = $habitLogService;
    }

    /**
     * @throws ServerErrorException
     * @throws AuthorizationException
     */
    public function update(UpdateStatusRequest $request, HabitLog $habitLog): JsonResponse
    {

        Gate::authorize('update-log', [$habit = $habitLog->habit, $habitLog]);
        $validated = $request->validated();

        try {
            $user = \Auth::user();

            $editable = $this->CheckforEdit($habitLog, $user);
            if (! $editable['status']) {
                return response()->json($editable, 400);
            }

            $this->habitLogService->UpdateHabitLogStatus($habitLog, $habitLog->status ^ 1);

            return response()->json([
                'status' => true,
                'message' => 'log updated successfully',
            ]);
        } catch (\Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }

    }

    /**
     * @return array
     *
     * @throws BadRequestException
     */
    public function CheckforEdit(HabitLog $habitLog, ?\App\Models\User $user)
    {
        if ($habitLog->status === null || ($habitLog->date > now($user->timezone)->format('Y-m-d'))) {
            $message = ($habitLog->date > now($user->timezone)->format('Y-m-d')) ? 'future day' : 'day off';

            return [
                'status' => false,
                'you cant edit habit log for this day its '.$message,
            ];

        }

        return [
            'status' => true,
        ];
    }
}
