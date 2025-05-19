<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use MongoDB\Client;
use App\Models\AuditLog;

class LoginController extends Controller
{
    //login page
    public function showloginPage()
    {
        return view('login');
    }

    //L
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            // Create audit log entry for non-Admin users
            if ($user->role !== 'Admin') {
                try {
                    AuditLog::create([
                        'user_id' => $user->_id,
                        'action' => 'Logged in',
                        'created_at' => new \MongoDB\BSON\UTCDateTime(now()->timestamp * 1000),
                    ]);
                } catch (\Exception $e) {
                    // Fail silently if audit log creation fails
                }
            }

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
