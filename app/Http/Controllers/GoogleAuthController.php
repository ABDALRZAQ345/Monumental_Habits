<?php

namespace App\Http\Controllers;

use App\Exceptions\ServerErrorException;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tymon\JWTAuth\Facades\JWTAuth;

class GoogleAuthController extends Controller
{
    /**
     * @throws ServerErrorException
     */
    public function handleGoogleUser($idToken): JsonResponse
    {
        try {
            db::beginTransaction();
            $googleUser = Http::get("https://oauth2.googleapis.com/tokeninfo?id_token={$idToken}")->json();

            if (! isset($googleUser['email'])) {
                return response()->json(['error' => 'Invalid Google ID token'], 401);
            }

            $user = User::firstOrCreate([
                'email' => $googleUser['email'],
            ], [
                'first_name' => $googleUser['name'],
                'last_name' => ' ',
                'google_id' => $googleUser['sub'],
                'password' => Hash::make(str()->random(24)),
            ]);

            $token = JWTAuth::fromUser($user);
            db::commit();

            return response()->json([
                'status' => true,
                'token' => $token,
            ]);
        } catch (\Exception $e) {
            db::rollBack();
            throw new ServerErrorException($e->getMessage());
        }

    }

    /**
     * @throws ServerErrorException
     */
    public function auth(Request $request): JsonResponse
    {
        $idToken = $request->input('id_token');

        if (! $idToken) {
            return response()->json(['error' => 'No token provided'], 400);
        }

        return $this->handleGoogleUser($idToken);

    }
}
