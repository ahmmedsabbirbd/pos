<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProfileUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;
    protected $fristName;
    protected $lastName;
    protected $mobile;
    protected $password;
    protected $profileName;
    /**
     * Create a new job instance.
     */
    public function __construct($id, $fristName, $lastName, $mobile, $password, $profileName)
    {
        $this->id = $id;
        $this->fristName = $fristName;
        $this->lastName = $lastName;
        $this->mobile = $mobile;
        $this->password = $password;
        $this->profileName = $profileName;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $profileData = [
            'fristName' => $this->fristName,
            'lastName' => $this->lastName,
            'mobile' => $this->mobile,
            'password' => $this->password,
            'avatar' =>  $this->profileName
        ];

        User::where('id','=', $this->id)
            ->update($profileData);
    }
}
