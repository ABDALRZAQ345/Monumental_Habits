<?php

use App\Http\Controllers\HabitLogController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss', 'auth:api'])->group(function () {

    Route::post('/habits/{habit}/logs', [HabitLogController::class, 'store']);

});
