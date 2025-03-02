<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FORBIDDEN extends Exception
{
    protected $message;

    public function __construct($message = 'Forbidden')
    {
        parent::__construct($message);
        $this->message = $message;
    }

    public function render(Request $request): \Illuminate\Http\JsonResponse
    {
        //
        return response()->json([
            'status' => false,
            'message' => $this->message,
        ], Response::HTTP_FORBIDDEN);
    }
}
