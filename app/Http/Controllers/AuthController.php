<?php

namespace App\Http\Controllers;

use App\Exceptions\ServerErrorException;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use App\Services\VerificationCodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    protected VerificationCodeService $verificationCodeService;

    public function __construct(VerificationCodeService $verificationCodeService)
    {
        $this->verificationCodeService = $verificationCodeService;
    }

    /**
     * @throws ServerErrorException
     * @throws \App\Exceptions\VerificationCodeException
     * @throws \Throwable
     */
    public function register(SignupRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $this->verificationCodeService->Check($validated['email'], $validated['code']);

        try {
            db::beginTransaction();
            $user = UserService::createUser($validated);
            $this->verificationCodeService->delete($validated['email']); // todo add that to observer
            db::commit();

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => JWTAuth::fromUser($user),
                'user' => UserResource::make($user),
            ]);
        } catch (\Exception $e) {
            db::rollBack();
            throw new ServerErrorException($e->getMessage());
        }
    }

    /**
     * @throws ServerErrorException
     * @throws \Throwable
     */
    public function login(LoginRequest $request): JsonResponse
    {

        $credentials = $request->only('email', 'password');

        try {
            db::beginTransaction();
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid email or password '], 401);
            }
            User::where('email', $credentials['email'])->update(['fcm_token' => $request->get('fcm_token')]);
            db::commit();
        } catch (JWTException $e) {
            db::rollBack();
            throw new ServerErrorException($e->getMessage());
        }

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
}
