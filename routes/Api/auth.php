<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\FcmTokenController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\VerificationCodeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss'])->group(function () {

    Route::post('/password/forget', [PasswordController::class, 'forget'])->middleware('throttle:change_password')->name('forget_password');

    Route::middleware('guest')->group(function () {
        Route::post('/verificationCode/send', [VerificationCodeController::class, 'send'])->middleware('throttle:send_confirmation_code')->name('verificationCode.check');

        Route::post('/verificationCode/check', [VerificationCodeController::class, 'check'])->middleware('throttle:check_verification_code')->name('verificationCode.check');

        Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:register')->name('register');

        Route::post('/login', [AuthController::class, 'login'])->name('login');

    });
    Route::middleware('auth:api')->group(function () {
        Route::post('password/reset', [PasswordController::class, 'reset'])->name('password.reset');
        Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::post('/send_fcm', [FcmTokenController::class, 'send'])->name('fcm.send');
        Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('throttle:rare')->name('refresh');
    });

});
