<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class ProfileUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;
    protected $fristName;
    protected $lastName;
    protected $mobile;
    protected $password;
    protected $profileName;
    protected $avatarPath;

    public $tries = 3;
    public $retryAfter = 60;
    /**
     * Create a new job instance.
     */
    public function __construct($fristName, $lastName, $mobile, $password, $profileName, $id, $avatarPath)
    {
        $this->id = $id;
        $this->fristName = $fristName;
        $this->lastName = $lastName;
        $this->mobile = $mobile;
        $this->password = $password;
        $this->profileName = $profileName;
        $this->avatarPath = $avatarPath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->profileName && $this->avatarPath) {
            echo $this->profileName;
            $profileImage = Image::make(storage_path('app/' . $this->avatarPath))->resize(150, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $currentPhoto = User::where('id', '=', $this->id)
                ->select('avatar')
                ->first();

            if($currentPhoto) {
                $avatarFilename = $currentPhoto->avatar;
                $filePath = public_path('avatars/' . $avatarFilename);
                if (File::exists($filePath)) {
                    if(File::delete($filePath)) {
                        if ($profileImage->save(public_path('avatars/' . $this->profileName))) {
                            File::delete(storage_path('app/' . $this->avatarPath));
                        }
                    }
                } else {
                    if ($profileImage->save(public_path('avatars/' . $this->profileName))) {
                        File::delete(storage_path('app/' . $this->avatarPath));
                    }
                }
            } else {
                if ($profileImage->save(public_path('avatars/' . $this->profileName))) {
                    File::delete(storage_path('app/' . $this->avatarPath));
                }
            }
        }

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
