<?php

use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss', 'auth:api'])->group(function () {

    Route::get('/chat', [MessageController::class, 'chat'])->name('chat');

    Route::post('/send-message', [MessageController::class, 'sendMessage'])
        ->name('send.message');
});
