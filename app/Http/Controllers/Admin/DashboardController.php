<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function showDashboardPage()
    {
        return view('admin.dashboard.dashboard');
    }
}