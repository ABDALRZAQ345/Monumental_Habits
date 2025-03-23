<?php

namespace App\Responses;

class LogedOutResponse
{
    public static function response(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully',
        ]);
    }
}
