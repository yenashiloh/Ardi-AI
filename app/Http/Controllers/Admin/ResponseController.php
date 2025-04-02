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

    public function destroy($id)
    {
        $response = Response::findOrFail($id);
        $response->delete();
    
        return redirect()->back()->with('success', 'Response deleted successfully!');
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
    public function showEditResponsePage($id)
    {
        $user = Auth::user();
        $response = Response::findOrFail($id); // Fetch the response
    
        return view('admin.content-management.response.edit-response', [
            'firstName' => $user->first_name,
            'lastName' => $user->last_name,
            'role' => $user->role,
            'response' => $response, // Pass response data to view
        ]);
    }
    
    public function updateResponse(Request $request, $id)
    {
        // Validate the incoming data
        $request->validate([
            'question' => 'required|string|max:255', // Adjust validation rules as needed
            'response' => 'required|string|max:1000',
        ]);

        // Find the response by ID and update it
        $response = Response::findOrFail($id);
        $response->question = $request->question;
        $response->response = $request->response;
        $response->save();

        // Redirect back to the edit page with a success message
        return redirect()->route('admin.content-management.response.edit-response', ['id' => $id])
                        ->with('success', 'Query and response updated successfully!');
    }

    
}