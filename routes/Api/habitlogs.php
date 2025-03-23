<?php

use App\Http\Controllers\Habit\HabitLogController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss', 'auth:api'])->group(function () {

    Route::post('/habit_logs/{habit_log}', [HabitLogController::class, 'update']);

});
