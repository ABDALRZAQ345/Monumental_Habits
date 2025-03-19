<?php

namespace App\Services;

use App\Exceptions\UNAuthorizedException;
use App\Exceptions\VerificationCodeException;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    protected VerificationCodeService $verificationCodeService;

    public function __construct(verificationCodeService $verificationCodeService)
    {
        $this->verificationCodeService = $verificationCodeService;
    }

    /**
     * @throws AuthenticationException
     * @throws \Throwable
     */
    public function attemptLogin(array $credentials, array $validated): string
    {
        if (! $token = JWTAuth::attempt($credentials)) {
            throw new UNAuthorizedException('Invalid email or password');
        }

        User::where('email', $validated['email'])->update([
            'fcm_token' => $validated['fcm_token'] ?? null,
            'timezone' => $validated['timezone'],
        ]);

        return $token;
    }

    /**
     * @throws VerificationCodeException
     * @throws \Throwable
     */
    public function attemptRegister($validated): User
    {

        return
            DB::transaction(function () use ($validated) {
                $this->verificationCodeService->Check($validated['email'], $validated['code']);
                $this->verificationCodeService->delete($validated['email']);

                return UserService::createUser($validated);
            });

    }
}
