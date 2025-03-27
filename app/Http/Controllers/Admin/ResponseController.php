<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Response;

class ResponseController extends Controller
{
    // Show the response management page
    public function showResponsePage()
    {
        $user = Auth::user();
        $responses = Response::all(); 
        return view('admin.content-management.response.response', [
            'firstName' => $user->first_name,
            'lastName' => $user->last_name,
            'role' => $user->role,
            'responses' => $responses
        ]);
    }

   // Show form to add response
   public function showAddResponsePage()
   {
       $user = auth()->user();
       return view('admin.content-management.response.add-response', [
           'firstName' => $user->first_name,
           'lastName' => $user->last_name,
           'role' => $user->role
       ]);
   }

   // Store response
    public function storeResponse(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
            'response' => 'required|string'
        ]);

        Response::create([
            'question' => $request->question,
            'response' => $request->response
        ]);

        return redirect()->back()->with('success', 'Response added successfully!');
    }


   // Get all responses
   public function getResponses()
   {
       $responses = Response::all();
       return response()->json($responses);
   }

    //Show the edit response page
    public function showEditResponsePage()
    {
        $user = Auth::user();
        return view('admin.content-management.response.edit-response', [
            'firstName' => $user->first_name,
            'lastName' => $user->last_name,
            'role' => $user->role
        ]);
    }
}