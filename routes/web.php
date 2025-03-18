<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ContentController;
Route::get('/', function () {
    return view('index');
});

Route::get('/index-2', [LoginController::class, 'showIndexTwoPage'])->name('index-2');
Route::get('/index-4', [LoginController::class, 'showIndexFourPage'])->name('index-4');

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

/********Users Management Page***********/
//Users Page
Route::get('/users', [UserController::class, 'showUsersPage'])->name('admin.users.users');
//Audit Trail Page
Route::get('/audit-trail', [UserController::class, 'showAuditTrailPage'])->name('admin.users-management.audit-trail');
//Create Account Page
Route::get('/create-account', [UserController::class, 'showCreateAccountPage'])->name('admin.users-management.create-account');

/********Content Management Page***********/
Route::get('/documents', [ContentController::class, 'showDocumentsPage'])->name('admin.content-management.documents');
Route::get('/add-query', [ContentController::class, 'showAddDocumentPage'])->name('admin.content-management.documents.add-document');
Route::get('/edit-query', [ContentController::class, 'showEditDocumentPage'])->name('admin.content-management.documents.edit-document');
