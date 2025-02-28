<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Admin;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use App\Mail\VerificationEmail;

class RegistrationController extends Controller
{
    public function register(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone',
            'address' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'agree_to_terms' => 'required|boolean',
            'company_name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'shop_name' => 'required|string|max:255',
            'website' => 'nullable|url|max:255',
            'how_you_heard' => 'required|string|max:255',
            'ecommerce_progress' => 'required|string|max:255',
            'order_management_tool' => 'required|string|max:255',
            'organization_size' => 'required|string|max:255',
            'business_model' => 'required|string|max:255',
        ]);

        // Generate a plain-text password
        $plainPassword = Str::random(12);

        // Create the user
        $user = User::create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'phone' => $validatedData['phone'],
            'address' => $validatedData['address'],
            'email' => $validatedData['email'],
            'password' => Hash::make($plainPassword),
            'agree_to_terms' => $validatedData['agree_to_terms'],
            'verification_token' => Str::random(60),
            'trial_ends_at' => now()->addDays(3),
            'email_verified' => false,
        ]);

        // Create the admin
        $admin = Admin::create([
            'user_id' => $user->id,
        ]);

        // Create the company
        $company = Company::create([
            'admin_id' => $admin->id,
            'company_name' => $validatedData['company_name'],
            'city' => $validatedData['city'],
            'shop_name' => $validatedData['shop_name'],
            'website' => $validatedData['website'],
            'how_you_heard' => $validatedData['how_you_heard'],
            'ecommerce_progress' => $validatedData['ecommerce_progress'],
            'order_management_tool' => $validatedData['order_management_tool'],
            'organization_size' => $validatedData['organization_size'],
            'business_model' => $validatedData['business_model'],
        ]);

        // Assign the 'admin' role
        $adminRole = Role::where('name', 'admin')->first();

        if (!$adminRole) {
            $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        }

        $user->assignRole($adminRole);

        // Send verification email
        Mail::to($user->email)->send(new VerificationEmail($user, $plainPassword));

        // Return response with 201 status code
        return response()->json([
            'message' => 'Registration successful! Please check your email to verify your account.',
            'user' => $user,
            'admin' => $admin,
            'company' => $company,
        ], 201);
    }
}
