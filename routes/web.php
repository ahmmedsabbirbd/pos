<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerificationMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// API Routes
Route::post('/user-login', [UserController::class, 'UserLogin']);
Route::post('/user-registration', [UserController::class, 'UserRegistration']);
Route::post('/user-send-otp-to-email', [UserController::class, 'UserSendOTPToEmail']);
Route::post('/otp-verify', [UserController::class, 'OTPVerify']);
Route::post('/set-password', [UserController::class, 'SetPassword'])->middleware(TokenVerificationMiddleware::class);
Route::get('/profile-update', [UserController::class, 'profileUpdate'])->middleware(TokenVerificationMiddleware::class);







// Logout
Route::get('/logout',[UserController::class,'userLogout'])->middleware(TokenVerificationMiddleware::class);

// Page Routes
Route::get('/userLogin',[UserController::class,'LoginPage'])->name('login');
Route::get('/userRegistration',[UserController::class,'RegistrationPage']);
Route::get('/sendOtp',[UserController::class,'SendOtpPage']);
Route::get('/verifyOtp',[UserController::class,'VerifyOTPPage']);
Route::get('/resetPassword',[UserController::class,'ResetPasswordPage'])->middleware(TokenVerificationMiddleware::class);
Route::get('/user-profile',[UserController::class,'ProfilePage'])->middleware(TokenVerificationMiddleware::class);



Route::get('/dashboard',[DashboardController::class,'DashboardPage'])->middleware(TokenVerificationMiddleware::class);

// Login With facebook
Route::get( 'auth/facebook', [SocialiteController::class, 'facebookRedirect'] )->name( 'facebook.login' );
Route::get( 'auth/facebook/callback', [SocialiteController::class, 'facebookCallback'] );
// Login With google
Route::get( 'auth/google', [SocialiteController::class, 'googleRedirect'] )->name( 'google.login' );
Route::get( 'auth/google/callback', [SocialiteController::class, 'googleCallback'] );