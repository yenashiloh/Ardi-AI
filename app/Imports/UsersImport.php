<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class UsersImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Normalize column names (case-insensitive support)
            $data = [
                'id_number' => $row['id number'] ?? $row['ID Number'] ?? null,
                'first_name' => $row['first name'] ?? $row['First Name'] ?? null,
                'last_name' => $row['last name'] ?? $row['Last Name'] ?? null,
                'email' => $row['email'] ?? $row['Email'] ?? null,
                'contact_number' => $row['contact number'] ?? $row['Contact Number'] ?? null,
                'role' => $row['role'] ?? $row['Role'] ?? null,
            ];

            // Validate Data
            $validator = Validator::make($data, [
                'id_number' => 'required|string|unique:users,id_number|max:20',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'contact_number' => 'required|string|max:20',
                'role' => 'required|string',
            ]);

            if ($validator->fails()) {
                continue; // Skip invalid rows
            }

            // Generate a secure random password
            $randomPassword = rand(1000, 9999) . strtoupper(substr($data['first_name'], 0, 2)) . strtoupper(substr($data['last_name'], 0, 2)) . '!@#$%^&*'[rand(0, 7)];

            // Insert into MongoDB
            User::create([
                'id_number' => $data['id_number'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'contact_number' => $data['contact_number'],
                'role' => $data['role'],
                'password' => Hash::make($randomPassword),
                'status' => 'Active',
                'is_archive' => 0,
            ]);
        }
    }
}
