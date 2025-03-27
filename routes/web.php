<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\AuthController;


Route::get('/', [AuthController::class, 'index'])->name('index');

Route::get('/index-2', [LoginController::class, 'showIndexTwoPage'])->name('index-2');
Route::get('/index-4', [LoginController::class, 'showIndexFourPage'])->name('index-4');

/********Login Page***********/
Route::get('/login', [LoginController::class, 'showLoginPage'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


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
Route::middleware(['auth', 'admin.auth'])->group(function () {
Route::get('/dashboard', [DashboardController::class, 'showDashboardPage'])->name('admin.dashboard.dashboard');

/********Users Management Page***********/
//Users Page
Route::get('/users', [UserController::class, 'showUsersPage'])->name('admin.users.users');
//Store Users Info
Route::post('/users/store', [UserController::class, 'storeUser'])->name('users.store');
//Import User Post
Route::post('/users/import', [UserController::class, 'importUsers']);
//Archive User Post
Route::post('/users/{id}/archive', [UserController::class, 'archiveUser'])->name('users.archive');
//Disable User Post
Route::post('/users/{id}/disable', [UserController::class, 'disableUser'])->name('users.disable');
//Activate User Post
Route::post('/users/{id}/activate', [UserController::class, 'activateUser'])->name('users.activate');
//Get User Details
Route::get('/admin/users/details/{idNumber}', [UserController::class, 'getUserDetails']);

Route::put('/admin/users/update/{idNumber}', [UserController::class, 'updateUser'])
    ->name('admin.users.update');
Route::get('/admin/users/edit/{idNumber}', [UserController::class, 'editUser'])
    ->name('admin.users.edit');
// Update user details


//Audit Trail Page
Route::get('/audit-trail', [UserController::class, 'showAuditTrailPage'])->name('admin.users-management.audit-trail');
//Create Account Page
Route::get('/create-account', [UserController::class, 'showCreateAccountPage'])->name('admin.users-management.create-account');

/********Content Management Page***********/
//Response Page
Route::get('/response', [ContentController::class, 'showResponsePage'])->name('admin.content-management.response');
//Add Query Page
Route::get('/add-query', [ContentController::class, 'showAddResponsePage'])->name('admin.content-management.response.add-response');
//Edit Query Page
Route::get('/edit-query', [ContentController::class, 'showEditResponsePage'])->name('admin.content-management.response.edit-response');

});
