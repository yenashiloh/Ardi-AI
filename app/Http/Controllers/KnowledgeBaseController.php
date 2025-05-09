<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KnowledgeBaseController extends Controller
{
    /**
     * Process a query synchronously (original method)
     */
    public function query(Request $request)
    {
        // Validate the request
        $request->validate([
            'query' => 'required|string|max:500',
        ]);

        try {
            // Get the current user or use a default if not authenticated
            $user = Auth::user();

            // Prepare data for Python API
            $data = [
                'query' => $request->input('query'),
                'user_id' => $user ? $user->id_number : 'guest',
                'first_name' => $user ? $user->first_name : 'Guest',
                'last_name' => $user ? $user->last_name : 'User',
                'role' => $user ? $user->role : 'standard',
                'department' => $user ? ($user->department ?? 'General') : 'General'
            ];

            // Log the request for debugging
            Log::info('Making request to Python API', [
                'url' => config('services.python_api.url', 'http://localhost:5000') . '/query',
                'data' => $data
            ]);

            // Call the Python API with increased timeout (2 minutes)
            $response = Http::timeout(120)->post(
                config('services.python_api.url', 'http://localhost:5000') . '/query',
                $data
            );

            // Log the response for debugging
            Log::info('Received response from Python API', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            // Return the response directly
            return response()->json($response->json());

        } catch (\Exception $e) {
            // Log the exception
            Log::error('Python API exception', [
                'message' => $e->getMessage(),
                'query' => $request->input('query'),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Knowledge base service unavailable',
                'message' => $e->getMessage()
            ], 503);
        }
    }

    /**
     * Submit a query for asynchronous processing
     */
    public function submitQuery(Request $request)
    {
        // Validate the request
        $request->validate([
            'query' => 'required|string|max:500',
        ]);

        try {
            // Get the current user
            $user = Auth::user();

            // Prepare data for Python API
            $data = [
                'query' => $request->input('query'),
                'user_id' => $user ? $user->id_number : 'guest',
                'first_name' => $user ? $user->first_name : 'Guest',
                'last_name' => $user ? $user->last_name : 'User',
                'role' => $user ? $user->role : 'standard',
                'department' => $user ? ($user->department ?? 'General') : 'General'
            ];

            // Log the request for debugging
            Log::info('Submitting query to Python API', [
                'url' => config('services.python_api.url', 'http://localhost:5000') . '/submit_query',
                'data' => $data
            ]);

            // Submit the query to the Python API
            $response = Http::timeout(10)->post(
                config('services.python_api.url', 'http://localhost:5000') . '/submit_query',
                $data
            );

            // Check if submission was successful
            if ($response->successful()) {
                return response()->json($response->json());
            }

            Log::error('Python API submission error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to submit query to knowledge base'
            ], 500);

        } catch (\Exception $e) {
            Log::error('Python API submission exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Knowledge base service unavailable',
                'message' => $e->getMessage()
            ], 503);
        }
    }

    /**
     * Check the status of an asynchronous query
     */
    public function checkQueryStatus($queryId)
    {
        try {
            // Check the status of the query
            $response = Http::timeout(5)->get(
                config('services.python_api.url', 'http://localhost:5000') . '/query_status/' . $queryId
            );

            // Return the response directly
            return response()->json($response->json());

        } catch (\Exception $e) {
            Log::error('Python API status check exception', [
                'message' => $e->getMessage(),
                'query_id' => $queryId,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to check query status',
                'message' => $e->getMessage()
            ], 503);
        }
    }
}
