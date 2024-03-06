<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function facebookRedirect()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function facebookCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();
            
            dd($user);

            return redirect()->route('dashboard');
        } catch(Exception $e) {
            dd($e->getMessage());
        }
    }
    public function googleRedirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            
            dd($user);

            return redirect()->route('dashboard');
        } catch(Exception $e) {
            dd($e->getMessage());
        }
    }
}
