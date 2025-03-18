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
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Find the user by email
        $user = User::where('email', $request->email)->first();

        // Check if user exists
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Email not found'
            ], 401);
        }

        // Check user status before attempting to authenticate
        if ($user->status === 'To Review') {
            return response()->json([
                'status' => false,
                'message' => 'Your account is pending approval. Please wait for admin review.'
            ], 403);
        }

        if ($user->status === 'Declined') {
            return response()->json([
                'status' => false,
                'message' => 'Your account has been declined. Please contact the administrator for more information.'
            ], 403);
        }

        // Check if user is approved
        if ($user->status !== 'Approved') {
            return response()->json([
                'status' => false,
                'message' => 'Your account is not active. Current status: ' . $user->status
            ], 403);
        }

        // Attempt authentication
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'Login successful',
                'user' => $user,
                'token' => $token
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid credentials'
        ], 401);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Successfully logged out'
        ]);
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
