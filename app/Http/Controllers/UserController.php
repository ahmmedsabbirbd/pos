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
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PhpParser\Node\Stmt\TryCatch;

class UserController extends Controller
{
    public function UserLogin(UserLoginRequest $request) {
        $res = User::where($request->input())->count();

        if($res==1) {
            $token = JWTToken::CreateToken($request->email);
            return response()->json([
                'msg'=>'success',
                'data'=>$token
            ]);
        }
        return response()->json([
            'msg'=>'failed',
            'data'=>'unauthorzies'
        ]);;

    }

    public function UserRegistration(UserRegistrationRequest $request) {
        $user = User::create($request->input());        
        if($user) {
            return 1;
        }
        return 0;
    }

    public function UserSendOTPToEmail(UserSendOTPToEmailRequest $request) {
        $otp = rand(1000, 9999);
        
        try {
            // Mail Send
            $OTPEmail = Mail::to($request->email)->send(new OTPEmail($otp));
    
            // Database Update
            $OTPEmailDatabase = User::where('email', $request->email)
            ->update([
                'otp'=> $otp,
            ]);
            return response()->json(['msg'=>'success','data'=>'Email Sended']);
        } catch (Exception $e) {
            return response()->json(['msg'=>'success','fail'=>'unauthorazed']);
        }
        
    }
    
    public function OTPVerify(OTPVerifyRequest $request) {
        $res = User::where($request->input())->count();
        if(1==$res) {
            User::where($request->input())->update([
                'otp'=>'0'
            ]);
            return response()->json(['msg'=>'success','data'=>'Verified']);            
        } else {
            return response()->json(['msg'=>'success','fail'=>'unauthorazed']);
        }
    }
    public function SetPassword(SetPasswordRequest $request) {
        $res = User::where($request->input())->update([
            'password'=>$request->password
        ]);
        if(1==$res) {
            return response()->json(['msg'=>'success','data'=>'Password Updated']);            
        } else {
            return response()->json(['msg'=>'success','fail'=>'unauthorazed']);
        }
    }

    public function ProfileUpdate() {
        
    }
}
