<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ServerErrorException extends Exception
{
    public function __construct(string $message = 'Server Error')
    {
        parent::__construct($message);
    }

    public function render(Request $request): JsonResponse
    {
        if (app()->environment('production')) {
            $this->message = 'something went wrong we will fix it as soon as possible try again later';
        }

        return response()->json([
            'status' => false,
            'message' => $this->message,
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
