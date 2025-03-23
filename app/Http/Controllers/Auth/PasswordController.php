<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\ServerErrorException;
use App\Exceptions\UNAuthorizedException;
use App\Exceptions\VerificationCodeException;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Password\ForgetPasswordRequest;
use App\Http\Requests\Password\ResetPasswordRequest;
use App\Models\User;
use App\Responses\LogedInResponse;
use App\Services\UserService;
use App\Services\VerificationCodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class PasswordController extends BaseController
{
    protected VerificationCodeService $verificationCodeService;

    public function __construct(VerificationCodeService $verificationCodeService)
    {
        $this->verificationCodeService = $verificationCodeService;
    }

    /**
     * @throws ServerErrorException
     * @throws VerificationCodeException
     * @throws \Throwable
     */
    public function forget(ForgetPasswordRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $this->verificationCodeService->Check($validated['email'], $validated['code']);

        try {
            DB::beginTransaction();
            $user = User::where('email', $validated['email'])->firstOrFail();
            UserService::updatePassword($user, $validated['password']);

            $this->verificationCodeService->delete($validated['email']);
            DB::commit();

            return LogedInResponse::response(JWTAuth::fromUser($user));

        } catch (\Exception $e) {
            DB::rollBack();
            throw new ServerErrorException($e->getMessage());
        }
    }

    /**
     * @throws ServerErrorException
     * @throws \Throwable
     */
    public function reset(ResetPasswordRequest $request): JsonResponse
    {

        $validated = $request->validated();

        $user = Auth::user();
        if (Hash::check($validated['old_password'], $user->password)) {

            UserService::updatePassword($user, $validated['new_password']);

            return response()->json([
                'status' => true,
                'message' => 'Password reset successfully!',
            ]);
        }
        throw new UNAuthorizedException('Wrong old password!');
    }
}
