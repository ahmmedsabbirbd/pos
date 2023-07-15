<?php

use App\Http\Controllers\UserController;
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

Route::post('/UserLogin', [UserController::class, 'UserLogin']);
Route::post('/UserRegistration', [UserController::class, 'UserRegistration']);
Route::post('/UserSendOTPToEmail', [UserController::class, 'UserSendOTPToEmail']);
Route::post('/OTPVerify', [UserController::class, 'OTPVerify']);
Route::post('/SetPassword', [UserController::class, 'SetPassword']);
Route::post('/ProfileUpdate', [UserController::class, 'ProfileUpdate']);