<?php

use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    dispatch(new \App\Jobs\DeleteExpiredCodes);
})->daily();

Schedule::command('habits:generate-logs')->yearly();
