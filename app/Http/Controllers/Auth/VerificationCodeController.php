<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\ServerErrorException;
use App\Exceptions\VerificationCodeException;
use App\Http\Controllers\BaseController;
use App\Http\Requests\VerificationCode\CheckVerificationCode;
use App\Http\Requests\VerificationCode\SendVerificationCodeRequest;
use App\Jobs\SendVerificationCode;
use App\Services\VerificationCodeService;
use Illuminate\Http\JsonResponse;

class VerificationCodeController extends BaseController
{
    //
    protected VerificationCodeService $verificationCodeService;

    public function __construct(VerificationCodeService $verificationCodeService)
    {
        $this->verificationCodeService = $verificationCodeService;
    }

    /**
     * @throws ServerErrorException
     */
    public function send(SendVerificationCodeRequest $request): JsonResponse
    {
        $validated = $request->validated();
        try {

            SendVerificationCode::dispatch($validated['email']);

            return response()->json([
                'status' => true,
                'message' => 'Verification code send successfully to '.$validated['email'],
            ]);
        } catch (\Exception $exception) {
            throw new ServerErrorException($exception->getMessage());
        }

    }

    /**
     * @throws VerificationCodeException
     */
    public function Check(CheckVerificationCode $request): JsonResponse
    {
        $validated = $request->validated();

        $this->verificationCodeService->Check($validated['email'], $validated['code']);

        return response()->json([
            'status' => true,
            'message' => 'Verification code is valid ',
        ]);

    }
}
