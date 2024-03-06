<?php

namespace App\Jobs;

use App\Mail\OTPEmail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Exception;

class SendEmailOTPJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $otp;
    protected $email;

    public $tries = 3;
    public $retryAfter = 60;

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
        /*if ('8' > $this->attempts()) {
            throw new Exception('kire');
        }*/

        // Mail Send
        Mail::to($this->email)->send(new OTPEmail($this->otp));

        // Database Update
        User::where('email', $this->email)
            ->update([
                'otp' => $this->otp,
            ]);
    }

    public function failed(Exception $failed)
    {
        Log::error('Job failed finally: '.$failed->getMessage());
    }
}
