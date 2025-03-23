<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss'])->group(function () {
    Route::post('/auth/google', [GoogleAuthController::class, 'auth'])->middleware('guest')->name('auth.google');

});
