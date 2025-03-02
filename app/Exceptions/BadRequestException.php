<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BadRequestException extends Exception
{
    public function __construct(string $message = 'Bad Request')
    {
        parent::__construct($message);
    }

    public function render(Request $request): JsonResponse
    {
        //
        return response()->json([
            'status' => false,
            'message' => $this->message,
        ], Response::HTTP_BAD_REQUEST);
    }
}
