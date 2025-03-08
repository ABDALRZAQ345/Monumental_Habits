<?php

use App\Http\Controllers\HomePageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss', 'auth:api'])->group(function () {

   Route::get('/homepage',HomepageController::class);
});
