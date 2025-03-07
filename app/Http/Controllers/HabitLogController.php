<?php

namespace App\Http\Controllers;

use App\Exceptions\ServerErrorException;
use App\Models\Habit;
use App\Models\HabitLog;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Http\Request;

class HabitLogController extends Controller
{
    /**
     * @throws ServerErrorException
     */
    public function store(Request $request,Habit $habit)
    {

    }


}
