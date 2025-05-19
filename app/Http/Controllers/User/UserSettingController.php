<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use MongoDB\Client;

class UserSettingController extends Controller
{
    //Update user information
     public function updateSettings(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'current_password' => 'required_with:new_password,password_confirmation',
            'new_password' => 'nullable|string|min:8|confirmed',
            'password_confirmation' => 'nullable|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Get authenticated user
            $user = Auth::user();
            
            // If trying to change password, verify current password
            if ($request->has('new_password') && !empty($request->new_password)) {
                // For Laravel's default authentication
                if (Hash::check($request->current_password, $user->password)) {
                    // Update password in the database
                    $this->updateUserPassword($user->_id, $request->new_password);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Current password is incorrect',
                    ], 422);
                }
            }

            // Update user information in MongoDB
            $updated = $this->updateUserInfo(
                $user->_id,
                $request->first_name,
                $request->last_name
            );

            if ($updated) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'User settings updated successfully',
                    'user' => [
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'email' => $user->email
                    ]
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to update user settings',
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while updating user settings',
                'debug' => $e->getMessage()
            ], 500);
        }
    }

    //Update user info
    private function updateUserInfo($userId, $firstName, $lastName)
    {
        try {
            // Connect to MongoDB
            $client = new Client(env('MONGODB_URI', 'mongodb://localhost:27017'));
            $collection = $client->selectDatabase(env('MONGODB_DATABASE', 'ardi-ai'))->users;
            
            // Create ObjectId from string ID
            $objectId = new \MongoDB\BSON\ObjectId($userId);
            
            // Update document
            $result = $collection->updateOne(
                ['_id' => $objectId],
                ['$set' => [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'updated_at' => new \MongoDB\BSON\UTCDateTime(time() * 1000)
                ]]
            );
            
            return $result->getModifiedCount() > 0;
        } catch (\Exception $e) {
            \Log::error('Error updating user info: ' . $e->getMessage());
            return false;
        }
    }

    //Update user password
    private function updateUserPassword($userId, $newPassword)
    {
        try {
            // Connect to MongoDB
            $client = new Client(env('MONGODB_URI', 'mongodb://localhost:27017'));
            $collection = $client->selectDatabase(env('MONGODB_DATABASE', 'ardi-ai'))->users;
            
            // Create ObjectId from string ID
            $objectId = new \MongoDB\BSON\ObjectId($userId);
            
            // Hash the new password
            $hashedPassword = Hash::make($newPassword);
            
            // Update document
            $result = $collection->updateOne(
                ['_id' => $objectId],
                ['$set' => [
                    'password' => $hashedPassword,
                    'updated_at' => new \MongoDB\BSON\UTCDateTime(time() * 1000)
                ]]
            );
            
            return $result->getModifiedCount() > 0;
        } catch (\Exception $e) {
            \Log::error('Error updating user password: ' . $e->getMessage());
            return false;
        }
    }

    //Get settings
     public function getSettings()
    {
        try {
            $user = Auth::user();
            
            return response()->json([
                'status' => 'success',
                'user' => [
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch user settings',
                'debug' => $e->getMessage()
            ], 500);
        }
    }
}