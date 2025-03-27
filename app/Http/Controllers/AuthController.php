<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
            // Check if user is logged in
        if (Auth::check()) {
            $user = Auth::user();
            $userName = $user->first_name . ' ' . $user->last_name;
            $userInitial = strtoupper(substr($userName, 0, 2));
    
            return view('index', [
                'userName' => $userName,
                'userEmail' => $user->email,
                'userInitial' => $userInitial
            ]);
        }
    
        return view('index');
    }
    
}
