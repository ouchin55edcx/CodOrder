<?php

// app/Http/Controllers/Auth/VerificationController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User; // Use the User model
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function verify($token)
    {
        $user = User::where('verification_token', $token)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Invalid verification token.',
            ], 404);
        }

        // Mark the email as verified
        $user->email_verified = true;
        $user->email_verified_at = now();
        $user->verification_token = null;
        $user->save();

        return response()->json([
            'message' => 'Email verified successfully!',
        ], 200);
    }
}
