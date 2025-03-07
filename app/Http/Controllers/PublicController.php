<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicController extends Controller
{
    //login page
    public function showloginPage()
    {
        return view ('login');
    }

    public function showSignUpPage()
    {
        return view ('sign-up');
    }

}
