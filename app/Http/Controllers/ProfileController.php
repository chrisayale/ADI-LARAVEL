<?php
// app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Show the profile for the authenticated user
     */
    public function show()
    {
        $user = auth()->user()->load([
            'savingsTransactions' => function($query) {
                $query->latest()->limit(10);
            },
            'loanApplications' => function($query) {
                $query->latest()->limit(5);
            }
        ]);

        // Calculate statistics based on role
        $stats = $this->getUserStats($user);

        return view('profile.show', compact('user', 'stats'));
    }

    /**
     * Show the form for editing the profile
     */
    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the profile
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string',
            'national_id' => 'required|string|unique:users,national_id,' . $user->id,
            'next_of_kin_name' => 'required|string|max:255',
            'next_of_kin_phone' => 'required|string|max:20',
            'next_of_kin_relationship' => 'required|string|max:100',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Add role-specific validation rules
        if ($user->hasRole('member')) {
            $rules['membership_number'] = 'nullable|string|unique:users,membership_number,' . $user->id;
            $rules['membership_date'] = 'nullable|date';
        } elseif ($user->hasRole('treasurer')) {
            $rules['employee_id'] = 'nullable|string|unique:users,employee_id,' . $user->id;
            $rules['employment_date'] = 'nullable|date';
            $rules['position'] = 'nullable|string|max:255';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'national_id' => $request->national_id,
            'next_of_kin_name' => $request->next_of_kin_name,
            'next_of_kin_phone' => $request->next_of_kin_phone,
            'next_of_kin_relationship' => $request->next_of_kin_relationship,
        ];

        // Add role-specific fields
        if ($user->hasRole('member')) {
            $updateData['membership_number'] = $request->membership_number;
            $updateData['membership_date'] = $request->membership_date;
        } elseif ($user->hasRole('treasurer')) {
            $updateData['employee_id'] = $request->employee_id;
            $updateData['employment_date'] = $request->employment_date;
            $updateData['position'] = $request->position;
        }

        // Handle password update if provided
        if ($request->filled('current_password') && $request->filled('new_password')) {
            $validator = Validator::make($request->all(), [
                'current_password' => 'required|current_password',
                'new_password' => 'required|string|min:8|confirmed',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $updateData['password'] = Hash::make($request->new_password);
        }

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $updateData['profile_photo'] = $path;
        }

        $user->update($updateData);

        return redirect()->route('profile.show')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Update profile photo only
     */
    public function updatePhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = auth()->user();

        // Delete old photo if exists
        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        $path = $request->file('profile_photo')->store('profile-photos', 'public');
        $user->update(['profile_photo' => $path]);

        return response()->json([
            'success' => true,
            'message' => 'Profile photo updated successfully.',
            'photo_url' => asset('storage/' . $path)
        ]);
    }

    /**
     * Get user statistics based on role
     */
    private function getUserStats($user)
    {
        $stats = [
            'member_since' => $user->created_at->format('F Y'),
            'total_savings' => $user->total_savings,
            'pending_savings' => $user->pending_savings,
        ];

        if ($user->hasRole('member')) {
            $stats['membership_number'] = $user->membership_number;
            $stats['membership_date'] = $user->membership_date;
            $stats['active_loans'] = $user->loanApplications()
                ->whereIn('status', ['approved', 'disbursed'])
                ->count();
            $stats['total_loans'] = $user->loanApplications()->count();
            $stats['loan_applications'] = $user->loanApplications()
                ->whereIn('status', ['pending', 'under_verification'])
                ->count();
        } elseif ($user->hasRole('treasurer')) {
            $stats['employee_id'] = $user->employee_id;
            $stats['position'] = $user->position;
            $stats['employment_date'] = $user->employment_date;
            $stats['assigned_loans'] = $user->assignedLoans()
                ->whereIn('status', ['under_verification', 'verified'])
                ->count();
            $stats['verified_loans'] = $user->assignedLoans()
                ->where('status', 'verified')
                ->count();
        } elseif ($user->hasRole('admin')) {
            $stats['total_members'] = User::role('member')->count();
            $stats['total_treasurers'] = User::role('treasurer')->count();
            $stats['pending_loans'] = \App\Models\LoanApplication::pending()->count();
        }

        return $stats;
    }

    /**
     * Get recent activity for the user
     */
    public function getRecentActivity()
    {
        $user = auth()->user();

        $activities = collect();

        // Get recent savings transactions
        $savings = $user->savingsTransactions()
            ->with('recorder')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'savings',
                    'icon' => $item->transaction_type === 'deposit' ? 'arrow-down' : 'arrow-up',
                    'color' => $item->transaction_type === 'deposit' ? 'success' : 'info',
                    'description' => $item->transaction_type === 'deposit' ? 'Deposit' : 'Withdrawal',
                    'amount' => $item->amount,
                    'date' => $item->created_at,
                    'reference' => $item->transaction_number,
                ];
            });

        $activities = $activities->concat($savings);

        // Get recent loan applications
        $loans = $user->loanApplications()
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($item) {
                $statusColors = [
                    'pending' => 'warning',
                    'under_verification' => 'info',
                    'verified' => 'primary',
                    'approved' => 'success',
                    'rejected' => 'danger',
                    'disbursed' => 'primary',
                    'completed' => 'success',
                ];

                return [
                    'type' => 'loan',
                    'icon' => 'hand-holding-usd',
                    'color' => $statusColors[$item->status] ?? 'secondary',
                    'description' => 'Loan Application',
                    'amount' => $item->amount_applied,
                    'date' => $item->created_at,
                    'reference' => $item->application_number,
                    'status' => $item->status,
                ];
            });

        $activities = $activities->concat($loans);

        // Sort by date
        $activities = $activities->sortByDesc('date')->values()->take(10);

        return response()->json($activities);
    }
}
