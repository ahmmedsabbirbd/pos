<?php

namespace App\Jobs;

use App\Mail\OTPEmail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailOTPJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $otp;
    public $email;

    /**
     * Create a new job instance.
     */
    public function __construct($email, $otp)
    {
        $this->email = $email;
        $this->otp = $otp;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Mail Send
        Mail::to($this->email)->send(new OTPEmail($this->otp));

        // Database Update
        User::where('email', $this->email)
            ->update([
                'otp' => $this->otp, // Remove extra dollar sign $
            ]);

    }
}
