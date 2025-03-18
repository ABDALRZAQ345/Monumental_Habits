<?php

use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss', 'auth:api'])->group(function () {


  Route::get('/chat',[MessageController::class,'index'])->name('chat');

    Route::post('/send-message', [MessageController::class, 'sendMessage'])
        ->name('send.message');
});
