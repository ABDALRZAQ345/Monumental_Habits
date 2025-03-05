<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/test', function (Request $request, \App\Services\HabitService $habitService) {
    $id = \App\Models\User::create([
        'name' => 'John',
        'email' => 'jdssd@example.com',
        'password' => Hash::make('password'),
    ])->id;
    $h = \App\Models\Habit::create([
        'name' => 's',
        'user_id' => $id,
        'days' => 1 + 2 + 8,
    ]);

    return response()->json([
        $h->getDays(),
    ]);

});
