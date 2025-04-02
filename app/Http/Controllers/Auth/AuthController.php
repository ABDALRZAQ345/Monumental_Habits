<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\ServerErrorException;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\SignupRequest;
use App\Responses\LogedInResponse;
use App\Responses\LogedOutResponse;
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

        return LogedInResponse::response(JWTAuth::fromUser($user));

    }

    /**
     * @throws ServerErrorException
     * @throws \Throwable
     */
    public function login(LoginRequest $request): JsonResponse
    {

        $credentials = $request->only('email', 'password');

        $token = $this->authService->attemptLogin($credentials, $request->validated());

        return LogedInResponse::response($token);

    }

    /**
     * @throws ServerErrorException
     */
    public function logout(): JsonResponse
    {
        try {
            auth()->logout();

            return LogedOutResponse::response();

        } catch (\Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }
    }

    public function refresh(): JsonResponse
    {
        $token = auth()->refresh();

        return LogedInResponse::response($token);

    }
}
