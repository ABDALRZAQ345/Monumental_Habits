<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\ServerErrorException;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\FcmTokenRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class FcmTokenController extends BaseController
{
    /**
     * @throws ServerErrorException
     */
    public function send(FcmTokenRequest $request): JsonResponse
    {
        $validated = $request->validated();
        try {

            $user = Auth::user();

            $user->update([
                'fcm_token' => $validated['fcm_token'],
            ]);

            return response()->json([
                'status' => true,
                'message' => 'token send successfully',
            ]);
        } catch (\Exception $exception) {
            throw new ServerErrorException($exception->getMessage());
        }

    }
}
