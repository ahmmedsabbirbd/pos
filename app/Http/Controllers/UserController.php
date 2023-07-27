<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Http\Requests\OTPVerifyRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\SetPasswordRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegistrationRequest;
use App\Http\Requests\UserSendOTPToEmailRequest;
use App\Jobs\ProfileUpdateJob;
use App\Jobs\SendEmailOTPJob;
use App\Models\User;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use HttpResponses;

    function LoginPage() {
        return view('pages.auth.login-page');
    }

    function RegistrationPage() {
        return view('pages.auth.registration-page');
    }
    function SendOtpPage() {
        return view('pages.auth.send-otp-page');
    }
    function VerifyOTPPage() {
        return view('pages.auth.verify-otp-page');
    }

    function ResetPasswordPage() {
        return view('pages.auth.reset-pass-page');
    }
    function ProfilePage() {
        return view('pages.dashboard.profile-page');
    }


    public function UserLogin(UserLoginRequest $request) {
        $res = User::where('email', $request->email)
        ->where('password', $request->password)
        ->select('id')
        ->first();

        if(null!==$res) {
            $token = JWTToken::CreateToken($request->email, $res->id);

            return response()->json([
                'status'=>'success',
                'message'=>'User Login Successful',
            ])->cookie('token', $token, 60*60*24);
        }
        return $this->error('unauthorzies');

    }

    public function UserRegistration(UserRegistrationRequest $request) {

        try {
            User::create([
                'fristName' => $request->fristName,
                'lastName' => $request->lastName,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'password' => $request->password,
            ]);

            return $this->success('User Registration Success');
        } catch (Exception $e) {
            return $this->error('User Registration Failed');
        }
    }

    public function UserSendOTPToEmail(UserSendOTPToEmailRequest $request) {
        $otp = rand(1000, 9999);

        try {
            SendEmailOTPJob::dispatch($request->email, $otp);

            return $this->success('4 digit code send to your email');
        } catch (Exception $e) {
            return $this->error('Some Think went worng');
        }

    }

    public function OTPVerify(OTPVerifyRequest $request) {
        $res = User::where('email', $request->email)
        ->where('otp', $request->otp)
        ->first();

        if($res) {
            $timeDifference = Carbon::now('Asia/Dhaka')->diffInMinutes(Carbon::parse($res['updated_at']));
            if($timeDifference > 5) {
                return $this->error('Time expired');
            }

            User::where('email', $request->email)->update([
                'otp'=>''
            ]);

            $token = JWTToken::CreateTokenSetPassword($request->email);

            return response()->json([
                'status'=>'success',
                'message'=>'OTP Verification Successful',
                // 'token'=>$token // when working mobile app desktop
            ])->cookie('token', $token, 60*60*2);
        } else {
            return $this->error('unauthorazed');
        }
    }

    public function SetPassword(SetPasswordRequest $request) {
        try {
            $email = $request->header('email');
            $password = $request->password;
            User::where('email', $email)->update([
                'password'=>$password
            ]);
            return $this->success('Password Updated', 200);
        } catch(Exception $e) {
            return $this->error('SomeThink Went Worng');
        }
    }

    public function userLogout() {
        return redirect('/userLogin')->cookie('token', '', -1);
    }

    public function profileDetails(Request $request) {
        try {
            $id = $request->header('id');
            $user = User::where('id','=', $id)
                ->select('fristName', 'lastName', 'email', 'mobile', 'avatar', 'password')
                ->first();

            return response()->json([
                'status' => 'success',
                'message' => 'Request Successful',
                'data' => $user
            ],200);
        } catch (Exception $e) {
            return $this->error('SomeThink Went Worng');
        }
    }

    public function DashBoardImage(Request $request) {
        try {
            $id = $request->header('id');
            $image = User::where('id','=', $id)
                ->select('avatar')
                ->first();
            return $image->avatar;
        } catch (Exception $e) {
            return $this->error('SomeThink Went Worng');
        }
    }

    public function profileUpdate(Request $request, ProfileUpdateRequest $updateRequest) {
        try {
            $id = $request->header('id');
            $profile = $request->file('avatar');

            if($profile) {
                $profileName = time().'-'.rand(10000000, 90000000).'.'.$profile->getClientOriginalExtension();
                $profileImage = Image::make($profile)->resize(150, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                $currentPhoto = User::where('id', '=', $id)
                    ->select('avatar')
                    ->first();

                if($currentPhoto) {
                    $avatarFilename = $currentPhoto->avatar;
                    $filePath = public_path('avatars/' . $avatarFilename);
                    if (File::exists($filePath)) {
                        if(File::delete($filePath)) {
                            $profileImage->save(public_path('avatars/'.$profileName));
                        }
                    } else {
                        $profileImage->save(public_path('avatars/'.$profileName));
                    }
                } else {
                    $profileImage->save(public_path('avatars/'.$profileName));
                }

            } else {
                $profileName = $updateRequest->haveAvatar;
            }

            User::where('id','=', $id)
                ->update([
                    'fristName' => $updateRequest->fristName,
                    'lastName' => $updateRequest->lastName,
                    'mobile' => $updateRequest->mobile,
                    'password' => $updateRequest->password,
                    'avatar' =>  $profileName
                ]);

            DB::commit();

            return $this->success('Profile Updated', 200);
        } catch (Exception $e) {
            DB::rollback(); // An error occurred, rollback the transaction
            return $this->error($e);
        }
    }
}
