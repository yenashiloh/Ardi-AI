<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function showDashboardPage()
    {
        $user = Auth::user();
        return view('admin.dashboard.dashboard', [
            'firstName' => $user->first_name,
            'lastName' => $user->last_name,
            'role' => $user->role
        ]);
    }
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}