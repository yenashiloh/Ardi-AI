<?php

namespace App\Http\Controllers\Admin;

use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\UserCredentialsMail;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    //Show Users Page
    public function showUsersPage()
    {
        return view('admin.users-management.users', [
            'users' => User::where('is_archive', 0)
                ->get(['id_number', 'first_name', 'last_name', 'email', 'role', 'status', 'created_at'])
        ]);
    }

    //Get User Details
    public function getUserDetails($idNumber)
    {
        $user = User::where('id_number', $idNumber)
            ->first([
                'id_number', 
                'first_name', 
                'last_name', 
                'email', 
                'role', 
                'status', 
                'created_at', 
                'updated_at'
            ]);
    
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
    
        return response()->json([
            'user' => [
                'Date Created' => $user->created_at->format('F d, Y, h:i A'),
                'ID Number' => $user->id_number,
                'First Name' => $user->first_name,
                'Last Name' => $user->last_name,
                'Email' => $user->email,
                'Role' => ucfirst($user->role),
                'Status' => $user->status,
            ]
        ]);
    }

    //Store User
    public function storeUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'contact_number' => 'required|string|max:20',
            'id_number' => 'required|string|unique:users,id_number|max:20',
            'role' => 'required|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
    
        try {
            // Generate password
            $randomNumbers = rand(1000, 9999);
            $firstTwoLetters = strtoupper(substr($request->first_name, 0, 2));
            $lastTwoLetters = strtoupper(substr($request->last_name, 0, 2));
            $specialChar = '!@#$%^&*'[rand(0, 7)];
            $generatedPassword = "{$randomNumbers}{$firstTwoLetters}{$lastTwoLetters}{$specialChar}";
            
            // Create user
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'contact_number' => $request->contact_number,
                'id_number' => $request->id_number,
                'role' => $request->role,
                'password' => Hash::make($generatedPassword),
                'status' => 'Active',
                'is_archive' => 0,
            ]);
            
            // Send email with credentials
            Mail::to($user->email)->send(new UserCredentialsMail($user, $generatedPassword));
            
            return response()->json([
                'status' => 'success',
                'message' => 'User added successfully, credentials sent to email.'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('User creation error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while adding the user. Please try again.',
                'error_details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    
    //Import User Credentials
    public function importUsers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'importFile' => 'required|file|mimes:csv,xls,xlsx'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
    
        try {
            $file = $request->file('importFile');
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
    
            if (count($rows) < 2) { // At least header + 1 data row
                return response()->json([
                    'status' => 'error',
                    'message' => 'File does not contain enough data'
                ], 422);
            }
    
            // Get header row and find required columns
            $headers = array_map('trim', array_map('strtolower', $rows[0]));
            
            $columnMap = [
                'id number' => -1,
                'first name' => -1,
                'last name' => -1,
                'email' => -1,
                'contact number' => -1,
                'role' => -1,
            ];
            
            // Map column positions with case-insensitive matching
            foreach ($headers as $index => $header) {
                $headerLower = strtolower($header);
                
                if ($headerLower === 'id number' || $headerLower === 'idnumber' || $headerLower === 'id_number') {
                    $columnMap['id number'] = $index;
                } elseif ($headerLower === 'first name' || $headerLower === 'firstname' || $headerLower === 'first_name') {
                    $columnMap['first name'] = $index;
                } elseif ($headerLower === 'last name' || $headerLower === 'lastname' || $headerLower === 'last_name') {
                    $columnMap['last name'] = $index;
                } elseif ($headerLower === 'email' || $headerLower === 'email address' || $headerLower === 'email_address') {
                    $columnMap['email'] = $index;
                } elseif ($headerLower === 'contact number' || $headerLower === 'contactnumber' || $headerLower === 'contact_number' || $headerLower === 'phone' || $headerLower === 'mobile') {
                    $columnMap['contact number'] = $index;
                } elseif ($headerLower === 'role' || $headerLower === 'user role' || $headerLower === 'user_role') {
                    $columnMap['role'] = $index;
                }
            }
    
            // Check if all required columns were found
            $missingColumns = [];
            foreach ($columnMap as $column => $index) {
                if ($index === -1) {
                    $missingColumns[] = $column;
                }
            }
            
            if (!empty($missingColumns)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Required columns not found: ' . implode(', ', $missingColumns)
                ], 422);
            }
    
            $successCount = 0;
            $failCount = 0;
            $errors = [];
    
            // Process data rows (skip header)
            for ($i = 1; $i < count($rows); $i++) {
                $row = $rows[$i];
                
                // Skip completely empty rows
                if (empty(array_filter($row, function($cell) { return !empty($cell); }))) {
                    continue;
                }
                
                $userData = [
                    'id_number' => trim($row[$columnMap['id number']]),
                    'first_name' => trim($row[$columnMap['first name']]),
                    'last_name' => trim($row[$columnMap['last name']]),
                    'email' => trim($row[$columnMap['email']]),
                    'contact_number' => trim($row[$columnMap['contact number']]),
                    'role' => trim($row[$columnMap['role']]),
                ];
                
                // Basic validation for each row
                $rowErrors = [];
                if (empty($userData['id_number'])) $rowErrors[] = 'Missing ID Number';
                if (empty($userData['first_name'])) $rowErrors[] = 'Missing First Name';
                if (empty($userData['last_name'])) $rowErrors[] = 'Missing Last Name';
                if (empty($userData['email'])) $rowErrors[] = 'Missing Email';
                if (empty($userData['contact_number'])) $rowErrors[] = 'Missing Contact Number';
                if (empty($userData['role'])) $rowErrors[] = 'Missing Role';
                
                if (!empty($rowErrors)) {
                    $failCount++;
                    $errors[] = "Row #{$i}: " . implode(', ', $rowErrors);
                    continue;
                }
                
                // Check if user with this ID or email already exists
                $existingUser = User::where('id_number', $userData['id_number'])
                                   ->orWhere('email', $userData['email'])
                                   ->first();
                                   
                if ($existingUser) {
                    $failCount++;
                    $errors[] = "Row #{$i}: User with ID Number {$userData['id_number']} or Email {$userData['email']} already exists";
                    continue;
                }
                
                try {
                    // Generate password
                    $randomNumbers = rand(1000, 9999);
                    $firstTwoLetters = strtoupper(substr($userData['first_name'], 0, 2));
                    $lastTwoLetters = strtoupper(substr($userData['last_name'], 0, 2));
                    $specialChar = '!@#$%^&*'[rand(0, 7)];
                    $generatedPassword = "{$randomNumbers}{$firstTwoLetters}{$lastTwoLetters}{$specialChar}";
                    
                    // Create user
                    $user = User::create([
                        'first_name' => $userData['first_name'],
                        'last_name' => $userData['last_name'],
                        'email' => $userData['email'],
                        'contact_number' => $userData['contact_number'],
                        'id_number' => $userData['id_number'],
                        'role' => $userData['role'],
                        'password' => Hash::make($generatedPassword),
                        'status' => 'Active',
                        'is_archive' => 0,
                    ]);
                    
                    // Send email with credentials
                    Mail::to($user->email)->send(new UserCredentialsMail($user, $generatedPassword));
                    
                    $successCount++;
                } catch (\Exception $e) {
                    $failCount++;
                    $errors[] = "Row #{$i}: " . $e->getMessage();
                }
            }
            
            $response = [
                'status' => 'success',
                'message' => "{$successCount} users imported successfully. {$failCount} failed.",
                'errors' => $errors
            ];
            
            return response()->json($response);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred during import: ' . $e->getMessage()
            ], 500);
        }
    }
    //Edit User Details
    public function editUser($idNumber)
    {
        $user = User::where('id_number', $idNumber)->first();
    
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
    
        return response()->json([
            'user' => $user
        ]);
    }
    //Update User Details 
    public function updateUser(Request $request, $idNumber)
    {
        $user = User::where('id_number', $idNumber)->first();
    
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
    
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required', 
                'email', 
                'max:255', 
                Rule::unique('users', 'email')->ignore($user->id)
            ],
            'role' => 'required|in:Admin,Collaborators,Team Leader,Non-Billable,Billable'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }
    
        try {
            // Check if email already exists for another user
            $existingEmail = User::where('email', $request->email)
                ->where('id_number', '!=', $idNumber)
                ->exists();
    
            if ($existingEmail) {
                return response()->json([
                    'error' => 'Email is already in use by another user.'
                ], 422);
            }
    
            $user->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'role' => $request->role
            ]);
    
            return response()->json([
                'message' => 'User Updated Successfully!',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while updating the user',
                'details' => $e->getMessage()
            ], 500);
        }
    }
    
    //Archive User 
    public function archiveUser($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_archive' => 1]);

        return response()->json(['message' => 'User archived successfully!']);
    }

    //Disable User
    public function disableUser($id)
    {
        $user = User::where('id_number', $id)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if ($user->status === 'Disabled') {
            return response()->json(['message' => 'User is already disabled'], 400);
        }

        $user->status = 'Disabled';
        $user->save();

        return response()->json(['message' => 'User has been disabled successfully']);
    }

    //Activate User
    public function activateUser($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->status === 'Disabled') {
            $user->status = 'Active';
            $user->save();

            return response()->json(['message' => 'User Activated Successfully']);
        }

        return response()->json(['message' => 'User is already active.'], 400);
    }

    //Show Audit Trail Page
    public function showAuditTrailPage()
    {
        return view('admin.users-management.audit-trail');
    }

    public function showCreateAccountPage()
    {
        return view('admin.users-management.create-account');
    }
}
