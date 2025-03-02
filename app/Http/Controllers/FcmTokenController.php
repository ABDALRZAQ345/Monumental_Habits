<?php

namespace App\Http\Controllers;

use App\Exceptions\ServerErrorException;
use App\Http\Requests\FcmTokenRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FcmTokenController extends Controller
{
    /**
     * @throws ServerErrorException
     */
    public function send(FcmTokenRequest $request): JsonResponse
    {
        $validated = $request->validated();
        try {

            $user = Auth::user();
            $user->fcm_token = $validated->fcm_token;
            $user->save();
            return response()->json([
                'status' => true,
                'message' => 'token send successfully',
            ]);
        } catch (\Exception $exception) {
            throw  new ServerErrorException($exception->getMessage());
        }

    }

}
