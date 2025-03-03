<?php

namespace App\Jobs;

use App\Exceptions\VerificationCodeException;
use App\Services\VerificationCodeService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendVerificationCode implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    protected $email;

    protected $verification_code_service;

    public function __construct($email)
    {
        $this->email = $email;
        $this->verification_code_service = new VerificationCodeService;
    }

    /**
     * Execute the job.
     *
     * @throws VerificationCodeException
     */
    public function handle(): void
    {
        $this->verification_code_service->delete($this->email);
        $this->verification_code_service->Send($this->email);
    }
}
