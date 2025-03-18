<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
public function showUsersPage()
    {
        return view('admin.users-management.users');
    }

    public function showAuditTrailPage()
    {
        return view('admin.users-management.audit-trail');
    }

    public function showCreateAccountPage()
    {
        return view('admin.users-management.create-account');
    }
}
