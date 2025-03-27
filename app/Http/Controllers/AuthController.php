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

            // Concatenate the user's first and last name
            $userName = $user->first_name . ' ' . $user->last_name;

            // Generate user initials (first two characters of the name, in uppercase)
            $userInitial = strtoupper(substr($userName, 0, 2));

            // Pass user details and responses to the 'index' view
            return view('index', [
                'userName' => $userName,
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
}
