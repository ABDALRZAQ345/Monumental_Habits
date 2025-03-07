<?php

namespace App\Http\Controllers;

use App\Exceptions\ServerErrorException;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\SignupRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Services\VerificationCodeService;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends BaseController
{
    protected VerificationCodeService $verificationCodeService;

    protected AuthService $authService;

    public function __construct(VerificationCodeService $verificationCodeService, AuthService $authService)
    {
        $this->verificationCodeService = $verificationCodeService;
        $this->authService = $authService;
    }

    /**
     * @throws ServerErrorException
     * @throws \App\Exceptions\VerificationCodeException
     * @throws \Throwable
     */
    public function register(SignupRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = $this->authService->attemptRegister($validated);

        return response()->json([
            'status' => true,
            'message' => 'User Created Successfully',
            'token' => JWTAuth::fromUser($user),
            'user' => UserResource::make($user),
        ]);

    }

    /**
     * @throws ServerErrorException
     * @throws \Throwable
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        $token = $this->authService->attemptLogin($credentials, $request->validated());

        return response()->json([
            'status' => true,
            'token' => $token,
        ]);

    }

    /**
     * @throws ServerErrorException
     */
    public function logout(): JsonResponse
    {
        try {
            auth()->logout();

            return response()->json([
                'status' => true,
                'message' => 'Logged out successfully',
            ]);
        } catch (\Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }
    }

    public function refresh(): JsonResponse
    {
        $token = auth()->refresh();

        return response()->json([
            'status' => true,
            'token' => $token,
        ]);

    }
}
