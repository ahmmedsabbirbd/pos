<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Http\Requests\OTPVerifyRequest;
use App\Http\Requests\SetPasswordRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegistrationRequest;
use App\Http\Requests\UserSendOTPToEmailRequest;
use App\Mail\OTPEmail;
use App\Models\User;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PhpParser\Node\Stmt\TryCatch;

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
                'message'=>'User Login Successful'
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
            // Mail Send
            Mail::to($request->email)->send(new OTPEmail($otp));
    
            // Database Update
            User::where('email', $request->email)
            ->update([
                'otp'=> $otp,
            ]);
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
            return $this->success('SomeThink Went Worng');;            
        }
    }
    
    public function userLogout() {
        return redirect('/userLogin')->cookie('token', '', -1); 
    }
    
    public function profileUpdate() {
        return "Good"; 
    }
    
}
