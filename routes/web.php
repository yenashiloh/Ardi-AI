<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;

Route::get('/', function () {
    return view('index');
});

Route::get('/login', [PublicController::class, 'showLoginPage'])->name('login');
Route::get('/sign-up', [PublicController::class, 'showSignUpPage'])->name('sign-up');