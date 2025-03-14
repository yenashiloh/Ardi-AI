<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\OtpVerification;
use App\Mail\OtpVerificationMail;

class RegisterController extends Controller
{
    // register page
    public function showSignUpPage()
    {
        return view('sign-up');
    }

    //Registration Post
    public function register(Request $request)
    {
        // Custom validation
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z\s]+$/'],
            'last_name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z\s]+$/'],
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]+$/',
                'confirmed',
                function ($attribute, $value, $fail) use ($request) {
                    $passwordLower = strtolower($value);

                    // Split first_name and last_name into words
                    $firstNameWords = array_filter(explode(' ', strtolower($request->input('first_name', ''))));
                    $lastNameWords = array_filter(explode(' ', strtolower($request->input('last_name', ''))));

                    // Check each word in first_name
                    foreach ($firstNameWords as $word) {
                        if (strlen($word) > 2 && str_contains($passwordLower, $word)) {
                            $fail('The password cannot contain your first name.');
                            return;
                        }
                    }

                    // Check each word in last_name
                    foreach ($lastNameWords as $word) {
                        if (strlen($word) > 2 && str_contains($passwordLower, $word)) {
                            $fail('The password cannot contain your last name.');
                            return;
                        }
                    }

                    // Split email local part into segments
                    $emailLocal = strtolower(explode('@', $request->input('email', ''))[0]);
                    $emailSegments = preg_split('/[_.-]/', $emailLocal);

                    // Check email local part and its segments
                    if (str_contains($passwordLower, $emailLocal)) {
                        $fail('The password cannot contain your email.');
                        return;
                    }

                    foreach ($emailSegments as $segment) {
                        if (strlen($segment) > 2 && str_contains($passwordLower, $segment)) {
                            $fail('The password cannot contain parts of your email.');
                            return;
                        }
                    }
                },
            ],
        ], [
            'first_name.regex' => 'The first name must contain only letters and spaces.',
            'last_name.regex' => 'The last name must contain only letters and spaces.',
            'password.regex' => 'The password must include uppercase, lowercase, numbers, and special characters.',
            'password.min' => 'The password must be at least 8 characters.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create user
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verification' => false,
            'status' => 'To Review'
        ]);

        // Generate OTP
        $otp = $this->generateOTP();

        // Save OTP
        OtpVerification::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(15),
        ]);

        // Send OTP email
        Mail::to($user->email)->send(new OtpVerificationMail($user->first_name, $otp));

        return response()->json([
            'user_id' => $user->id
        ], 201);
    }

    // Generate a 6-digit OTP
    private function generateOTP()
    {
        return rand(100000, 999999);
    }

    // Verify OTP
    public function verifyOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'otp' => 'required|string|min:6|max:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $userId = (string)$request->user_id;
        $otpCode = (string)$request->otp;

        $otpVerification = OtpVerification::on('mongodb')
            ->where('user_id', '=', $userId)
            ->first();

        if (!$otpVerification) {
            return response()->json(['message' => 'Invalid or expired OTP'], 400);
        }

        if ((string)$otpVerification->otp !== $otpCode) {
            return response()->json(['message' => 'Invalid or expired OTP'], 400);
        }

        // Check if the OTP has expired
        if (now()->gt($otpVerification->expires_at)) {
            return response()->json(['message' => 'OTP has expired'], 400);
        }

        try {
            // Find the user in the MongoDB database
            $user = User::on('mongodb')->find($userId);
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            // Mark the email as verified
            $user->email_verification = true;
            $user->save();

            // Delete the OTP record after successful verification
            $otpVerification->delete();

            return response()->json(['message' => 'Email verified successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Verification failed: ' . $e->getMessage()], 500);
        }
    }

    // Resend OTP
    public function resendOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::find($request->user_id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Delete any existing OTPs for this user
        OtpVerification::where('user_id', $user->id)->delete();

        // Generate new OTP
        $otp = $this->generateOTP();

        // Save OTP
        OtpVerification::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(15),
        ]);

        // Send OTP email
        Mail::to($user->email)->send(new OtpVerificationMail($user->first_name, $otp));

        return response()->json(['message' => 'OTP resent successfully'], 200);
    }

    //Verification Form
    public function showVerificationForm(Request $request)
    {
        $userId = $request->query('user_id');

        if (!$userId) {
            return redirect('/login')->with('error', 'Invalid verification link');
        }

        $user = User::find($userId);

        if (!$user) {
            return redirect('/login')->with('error', 'User not found');
        }

        if ($user->email_verification) {
            return redirect('/login')->with('success', 'Email already verified. Please login.');
        }

        return view('emails.verify-email', compact('userId'));
    }
}
