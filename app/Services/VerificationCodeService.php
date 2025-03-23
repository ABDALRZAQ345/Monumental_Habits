<?php

namespace App\Services;

use App\Exceptions\VerificationCodeException;
use App\Mail\SendEmail;
use App\Models\VerificationCode;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class VerificationCodeService
{
    public static function Send($email): void
    {
        $code = rand(100000, 999999);

        VerificationCode::create([
            'email' => $email,
            'code' => Hash::make($code),
            'expires_at' => now()->addMinutes(30),
        ]);
        Log::channel('verification_code')->info('the code for '.$email.' is '.$code);
        $message = " code : {$code}";
        $subject = 'Verification Code';
        Mail::to($email)->send(new SendEmail($message, $subject));
    }

    /**
     * @throws VerificationCodeException
     */
    public static function Check($email, $code): void
    {
        $verificationCode = VerificationCode::where('email', $email)->first();

        if (! $verificationCode || ! Hash::check($code, $verificationCode->code)) {
            throw new VerificationCodeException('incorrect code');
        }
        if ($verificationCode->isExpired()) {
            throw new VerificationCodeException('Expired code');
        }
        $verificationCode->verified_at = now();
        $verificationCode->expires_at = now()->addMinutes(60);
        $verificationCode->save();

    }

    /**
     * @throws VerificationCodeException
     */
    public function delete($email): void
    {

        VerificationCode::where(
            'email',
            $email
        )->delete();

    }
}
