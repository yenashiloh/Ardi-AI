<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use MongoDB\Client;

class LoginController extends Controller
{
    //login page
    public function showloginPage()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
    
            if ($user->role === 'Admin') {
                return redirect()->route('admin.dashboard.dashboard');
            } else {
                return redirect()->route('index');
            }
        }
    
        return back()->with('error', 'Invalid email or password.');
    }
    
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function showIndexTwoPage()
    {
        return view('index-2');
    }

    public function showIndexFourPage()
    {
        return view('index-4');
    }
    
}
