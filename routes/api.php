<?php

use App\Http\Controllers\HomePageController;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss', 'auth:api'])->group(function () {

   Route::get('/homepage',HomepageController::class);
});
Route::get('/test',function (){


})->middleware('auth:api');
