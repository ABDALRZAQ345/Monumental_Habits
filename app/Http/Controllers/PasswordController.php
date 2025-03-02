<?php

namespace App\Http\Controllers;

use App\Exceptions\ServerErrorException;
use App\Exceptions\VerificationCodeException;
use App\Http\Requests\Password\ForgetPasswordRequest;
use App\Http\Requests\Password\ResetPasswordRequest;
use App\Models\User;
use App\Services\UserService;
use App\Services\VerificationCodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    protected VerificationCodeService $verificationCodeService;

    public function __construct(VerificationCodeService $verificationCodeService)
    {
        $this->verificationCodeService = $verificationCodeService;
    }

    /**
     * @throws ServerErrorException
     * @throws VerificationCodeException
     */
    public function forget(ForgetPasswordRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $this->verificationCodeService->Check($validated['email'], $validated['code']);

        try {
            db::beginTransaction();
            $user = User::where('email', $validated['email'])->firstOrFail();

            UserService::updatePassword($user, $validated['password']);

            $this->verificationCodeService->delete($validated['email']);
            db::commit();
            return response()->json([
                'status' => true,
                'message' => 'Password changed successfully!',
            ]);
        } catch (\Exception $e) {
            db::rollBack();
            throw new ServerErrorException($e->getMessage());
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function reset(ResetPasswordRequest $request): JsonResponse
    {

        $validated = $request->validated();


        try {
            db::beginTransaction();
            $user = Auth::user();
            if (Hash::check($validated['old_password'], $user->password)) {

                UserService::updatePassword($user, $validated['new_password']);
                db::commit();
                return response()->json([
                    'status' => true,
                    'message' => 'Password reset successfully!',
                ]);
            }
            throw  new ServerErrorException('Wrong old password!');

        } catch (\Exception $e) {
            db::rollBack();
            throw new ServerErrorException($e->getMessage());
        }

    }
}
