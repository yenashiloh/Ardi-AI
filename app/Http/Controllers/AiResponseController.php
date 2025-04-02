<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Response;
use MongoDB\BSON\ObjectId;


class AiResponseController extends Controller
{
    public function getById($id)
    {
        try {
            // Check if it looks like a valid ObjectID
            if (strlen($id) == 24 && ctype_xdigit($id)) {
                $response = Response::where('_id', new ObjectId($id))->first();
            } else {
                // If not, try direct match (for testing)
                $response = Response::where('_id', $id)->first();
            }
            
            if ($response) {
                return response()->json([
                    'question' => $response->question,
                    'response' => $response->response
                ]);
            }
            
            return response()->json(['error' => 'Response not found'], 404);
            
        } catch (\Exception $e) {
            \Log::error('Error retrieving MongoDB response', [
                'error' => $e->getMessage(),
                'id' => $id
            ]);
            
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }
}
