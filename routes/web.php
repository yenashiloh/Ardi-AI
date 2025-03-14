<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return view('index');
});

/********Login Page***********/
Route::get('/login', [LoginController::class, 'showLoginPage'])->name('login');


/********Sign Up Page***********/
Route::get('/sign-up', [RegisterController::class, 'showSignUpPage'])->name('sign-up');
//Sign Up Post
Route::post('/register', [RegisterController::class, 'register'])->name('register');
//Verify OTP Post
Route::post('/verify-otp', [RegisterController::class, 'verifyOTP']);
//Resend OTP Post
Route::post('/resend-otp', [RegisterController::class, 'resendOTP']);
//Verify Email Template Email
Route::get('/emails/verify-email', [RegisterController::class, 'showVerificationForm'])->name('verification.show');


/************************************************ADMIN SIDE***********************************************************/

/********Dashboard Page***********/
Route::get('/dashboard', [DashboardController::class, 'showDashboardPage'])->name('admin.dashboard.dashboard');
