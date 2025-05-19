<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ResponseController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AiResponseController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\KnowledgeBaseController;
use App\Http\Controllers\User\UserSettingController;


Route::get('/', [AuthController::class, 'index'])->name('index');

Route::get('/responses/{id}', [AiResponseController::class, 'getById'])->name('responses.get');
Route::get('/user/settings', [UserSettingController::class, 'getSettings'])->name('getSettings');
Route::post('/user/settings/update', [UserSettingController::class, 'updateSettings'])->name('updateSettings');

/**************Login Page******************/
Route::get('/login', [LoginController::class, 'showLoginPage'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/**************Sign Up Page***************/
Route::get('/sign-up', [RegisterController::class, 'showSignUpPage'])->name('sign-up');
//Sign Up Post
Route::post('/register', [RegisterController::class, 'register'])->name('register');
//Verify OTP Post
Route::post('/verify-otp', [RegisterController::class, 'verifyOTP']);
//Resend OTP Post
Route::post('/resend-otp', [RegisterController::class, 'resendOTP']);
//Verify Email Template Email
Route::get('/emails/verify-email', [RegisterController::class, 'showVerificationForm'])->name('verification.show');

Route::get('/check-auth-status', function () {
    return response()->json([
        'authenticated' => Auth::check()
    ]);
});

/************Dashboard Page**************/
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'showDashboardPage'])->name('admin.dashboard.dashboard');

    //Logout
    Route::post('/logout', [DashboardController::class, 'logout'])->name('logout');
    Route::delete('/documents/{id}', [DocumentController::class, 'destroyDocument'])->name('admin.documents.destroy');

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
    //Update user Details
    Route::put('/admin/users/update/{idNumber}', [UserController::class, 'updateUser'])->name('admin.users.update');
    //View Edit Details
    Route::get('/admin/users/edit/{idNumber}', [UserController::class, 'editUser'])->name('admin.users.edit');
    //restore User
    Route::post('/users/restore', [UserController::class, 'restore'])->name('users.restore');


    //Audit Trail Page
    Route::get('/audit-trail', [UserController::class, 'showAuditTrailPage'])->name('admin.users-management.audit-trail');
    //Create Account Page
    Route::get('/create-account', [UserController::class, 'showCreateAccountPage'])->name('admin.users-management.create-account');

    /********Response Management Page***********/
    //Response Page
    Route::get('/response', [ResponseController::class, 'showResponsePage'])->name('admin.content-management.response');
    //Add Query Page
    Route::get('/add-query', [ResponseController::class, 'showAddResponsePage'])->name('admin.content-management.response.add-response');
    //Edit Query Page
    Route::get('/edit-query/{id}', [ResponseController::class, 'showEditResponsePage'])->name('admin.content-management.response.edit-response');
    //Update Query Page
    Route::put(
        '/admin/content-management/response/update-response/{id}',
        [ResponseController::class, 'updateResponse']
    )->name('admin.content-management.response.update-response');

    //Sore Query
    Route::post('/store-response', [ResponseController::class, 'storeResponse'])->name('response.store');
    //Get Response
    Route::get('/responses', [ResponseController::class, 'getResponses'])->name('response.list');
    //Delete Response
    Route::delete('/admin/content-management/response/{id}', [ResponseController::class, 'destroy'])->name('response.delete');

    /********Documents Management Page***********/
    //Document Page
    Route::get('/documents', [DocumentController::class, 'showDocumentsPage'])->name('admin.documents.documents');
    //Store Document
    Route::post('/documents', [DocumentController::class, 'storeDocument'])->name('documents.store');
    //Delete Document
    Route::delete('/documents/{document_id}/delete', [DocumentController::class, 'deleteDocument'])->name('documents.delete');
    //Update Document
    Route::put('/documents/{id}', [DocumentController::class, 'updateDocument'])->name('admin.documents.update');

    //Edit Profile
    Route::get('/admin-profile', [ProfileController::class, 'showProfilePage'])->name('admin.profile');
    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('admin.profile.update');

    //Show Archived Users
    Route::get('/archived-users', [UserController::class, 'showArchivedUsersPage'])->name('admin.archive');
});

// Knowledge Base routes
Route::middleware(['auth'])->group(function () {
    // Synchronous API (original)
    Route::post('/knowledge-base/query', [KnowledgeBaseController::class, 'query'])->name('knowledge.query');

    // Asynchronous API (new)
    Route::post('/knowledge-base/submit', [KnowledgeBaseController::class, 'submitQuery'])->name('knowledge.submit');
    Route::get('/knowledge-base/status/{queryId}', [KnowledgeBaseController::class, 'checkQueryStatus'])->name('knowledge.status');
});
