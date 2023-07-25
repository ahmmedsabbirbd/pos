<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
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
// Page Routes
Route::get('/userLogin',[UserController::class,'LoginPage'])->name('login');
Route::get('/userRegistration',[UserController::class,'RegistrationPage']);
Route::get('/sendOtp',[UserController::class,'SendOtpPage']);
Route::get('/verifyOtp',[UserController::class,'VerifyOTPPage']);
Route::get('/resetPassword',[UserController::class,'ResetPasswordPage'])->middleware(TokenVerificationMiddleware::class);
Route::get('/user-profile',[UserController::class,'ProfilePage'])->middleware(TokenVerificationMiddleware::class);
Route::post('/photo-update',[UserController::class,'processImage'])->middleware(TokenVerificationMiddleware::class);
Route::get('/dashboard-image',[UserController::class,'DashBoardImage'])->middleware(TokenVerificationMiddleware::class);



// API Routes
Route::post('/user-login', [UserController::class, 'UserLogin']);
Route::post('/user-registration', [UserController::class, 'UserRegistration']);
Route::post('/user-send-otp-to-email', [UserController::class, 'UserSendOTPToEmail']);
Route::post('/otp-verify', [UserController::class, 'OTPVerify']);
Route::post('/set-password', [UserController::class, 'SetPassword'])->middleware(TokenVerificationMiddleware::class);
Route::get('/profile-details', [UserController::class, 'profileDetails'])->middleware(TokenVerificationMiddleware::class);
Route::post('/profile-update', [UserController::class, 'profileUpdate'])->middleware(TokenVerificationMiddleware::class);


// Customer API
Route::post("/create-customer",[CustomerController::class,'CustomerCreate'])->middleware([TokenVerificationMiddleware::class]);
Route::get("/list-customer",[CustomerController::class,'CustomerList'])->middleware([TokenVerificationMiddleware::class]);
Route::delete("/delete-customer",[CustomerController::class,'CustomerDelete'])->middleware([TokenVerificationMiddleware::class]);
Route::post("/update-customer",[CustomerController::class,'CustomerUpdate'])->middleware([TokenVerificationMiddleware::class]);



// Category API
Route::post("/create-category",[CategoryController::class,'CategoryCreate'])->middleware([TokenVerificationMiddleware::class]);
Route::get("/list-category",[CategoryController::class,'CategoryList'])->middleware([TokenVerificationMiddleware::class]);
Route::post("/delete-category",[CategoryController::class,'CategoryDelete'])->middleware([TokenVerificationMiddleware::class]);
Route::post("/update-category",[CategoryController::class,'CategoryUpdate'])->middleware([TokenVerificationMiddleware::class]);


// Product API
Route::post("/create-product",[ProductController::class,'CreateProduct'])->middleware([TokenVerificationMiddleware::class]);
Route::post("/delete-product",[ProductController::class,'DeleteProduct'])->middleware([TokenVerificationMiddleware::class]);
Route::post("/update-product",[ProductController::class,'UpdateProduct'])->middleware([TokenVerificationMiddleware::class]);
Route::get("/list-product",[ProductController::class,'ProductList'])->middleware([TokenVerificationMiddleware::class]);


// Dashboard API
Route::get("/total-customer",[DashboardController::class,'TotalCustomer'])->middleware([TokenVerificationMiddleware::class]);
Route::get("/total-category",[DashboardController::class,'TotalCategory'])->middleware([TokenVerificationMiddleware::class]);
Route::get("/total-product",[DashboardController::class,'TotalProduct'])->middleware([TokenVerificationMiddleware::class]);



// Logout
Route::get('/logout',[UserController::class,'userLogout'])->middleware(TokenVerificationMiddleware::class);
Route::get('/dashboard',[DashboardController::class,'DashboardPage'])->middleware(TokenVerificationMiddleware::class);

// Login With facebook
Route::get( 'auth/facebook', [SocialiteController::class, 'facebookRedirect'] )->name( 'facebook.login' );
Route::get( 'auth/facebook/callback', [SocialiteController::class, 'facebookCallback'] );
// Login With google
Route::get( 'auth/google', [SocialiteController::class, 'googleRedirect'] )->name( 'google.login' );
Route::get( 'auth/google/callback', [SocialiteController::class, 'googleCallback'] );
