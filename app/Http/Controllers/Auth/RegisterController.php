<?php
// app/Http/Controllers/Auth/RegisterController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'national_id' => 'required|string|unique:users|max:50',
            'next_of_kin_name' => 'required|string|max:255',
            'next_of_kin_phone' => 'required|string|max:20',
            'next_of_kin_relationship' => 'required|string|max:100',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Generate membership number
        $memberCount = User::count() + 1;
        $membershipNumber = 'MEM-' . date('Y') . '-' . str_pad($memberCount, 5, '0', STR_PAD_LEFT);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'national_id' => $request->national_id,
            'membership_number' => $membershipNumber,
            'membership_date' => now(),
            'status' => 'active',
            'next_of_kin_name' => $request->next_of_kin_name,
            'next_of_kin_phone' => $request->next_of_kin_phone,
            'next_of_kin_relationship' => $request->next_of_kin_relationship,
            'password' => Hash::make($request->password),
        ]);

        // Assign member role
        $user->assignRole('member');

        // Log the user in
        auth()->login($user);

        return redirect()->route('member.dashboard');
    }
}
