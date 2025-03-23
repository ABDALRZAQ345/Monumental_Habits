<?php

use App\Http\Controllers\Habit\HabitController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss', 'auth:api'])->group(function () {

    Route::post('/habits', [HabitController::class, 'store'])->name('habits.store');
    Route::put('/habits/{habit}', [HabitController::class, 'update'])->name('habits.update');
    Route::delete('/habits/{habit}', [HabitController::class, 'delete'])->name('habits.destroy');
    Route::get('/habits/{habit}', [HabitController::class, 'show'])->name('habits.show');
    Route::get('/habits', [HabitController::class, 'index'])->name('habits.index');

});
