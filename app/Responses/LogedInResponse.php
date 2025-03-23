<?php

namespace App\Responses;

class LogedInResponse
{
    public static function response($token): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'message' => true,
            'token' => $token,
        ]);
    }
}
