<?php

use App\Http\Controllers\HabitController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss', 'auth:api'])->group(function () {

    Route::post('/habits', [HabitController::class, 'store']);

});
