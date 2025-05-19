<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Response;


class AuthController extends Controller
{
    //Show index page
   public function index()
    {
        // Retrieve all responses outside of the authentication check
        $responses = Response::all(); 

        // Check if user is logged in
        if (Auth::check()) {
            // Retrieve the authenticated user
            $user = Auth::user();

            // Get first and last name separately
            $userFirstName = $user->first_name;
            $userLastName = $user->last_name;

            // Generate user initials (first character of first name and first character of last name, in uppercase)
           $userInitial = strtoupper(substr($userFirstName, 0, 1) . substr($userLastName, 0, 1));

            // Pass user details and responses to the 'index' view
            return view('index', [
                'userFirstName' => $userFirstName,
                'userLastName' => $userLastName,
                'userEmail' => $user->email,
                'userInitial' => $userInitial,
                'responses' => $responses
            ]);
        }

        // If user is not logged in, pass only responses
        return view('index', [
            'responses' => $responses
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('index')
            ->withHeaders([
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => 'Sat, 01 Jan 2000 00:00:00 GMT',
            ]);
    }

}
