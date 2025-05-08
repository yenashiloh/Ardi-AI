<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class ProfileController extends Controller
{
    public function showProfilePage()
    {
        $user = Auth::user();
        return view('admin.profile.edit-profile', [
            'firstName' => $user->first_name,
            'lastName' => $user->last_name,
            'role' => $user->role,
            'user'=> $user
        ]); 
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
    
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|max:255|unique:users,email,' . $user->_id,
            'contact_number' => 'required|string|max:20',
            'id_number'  => 'required|string|max:50',
            'password'   => 'nullable|string|min:6|confirmed',
        ]);
    
        $updateData = [
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'contact_number' => $validated['contact_number'],
            'id_number' => $validated['id_number']
        ];
    
        // Add password to update data only if provided
        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }
    
        // Use the MongoDB-specific update method
        User::where('_id', $user->_id)->update($updateData);
    
        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}
