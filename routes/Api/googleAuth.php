<?php

use App\Http\Controllers\GoogleAuthController;

use Illuminate\Support\Facades\Route;

Route::post('/auth/google', [GoogleAuthController::class, 'auth'])->name('auth.google');

