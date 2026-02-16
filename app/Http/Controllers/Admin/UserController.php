<?php
// app/Http/Controllers/Admin/UserController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('membership_number', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Filter by role
        if ($request->has('role') && $request->role != 'all') {
            $query->role($request->role);
        }

        $users = $query->with('roles')->latest()->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

   public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'phone_number' => 'required|string|max:20',
        'address' => 'required|string',
        'national_id' => 'required|string|unique:users',
        'membership_number' => 'nullable|string|unique:users',
        'membership_date' => 'nullable|date',
        'next_of_kin_name' => 'required|string|max:255', // Required for all
        'next_of_kin_phone' => 'required|string|max:20', // Required for all
        'next_of_kin_relationship' => 'required|string|max:100', // Required for all
        'role' => 'required|in:member,treasurer',
        'status' => 'required|in:active,inactive,pending',
        'employee_id' => 'nullable|string|unique:users,employee_id,NULL,id',
        'employment_date' => 'nullable|date',
        'position' => 'nullable|string|max:255',
        'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    $userData = [
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'phone_number' => $request->phone_number,
        'address' => $request->address,
        'national_id' => $request->national_id,
        'status' => $request->status,
        // Next of kin fields for ALL users
        'next_of_kin_name' => $request->next_of_kin_name,
        'next_of_kin_phone' => $request->next_of_kin_phone,
        'next_of_kin_relationship' => $request->next_of_kin_relationship,
    ];

    // Handle role-specific fields and auto-generation
    if ($request->role === 'member') {
        // Auto-generate membership number if not provided
        if (empty($request->membership_number)) {
            $userData['membership_number'] = $this->generateMembershipNumber();
        } else {
            $userData['membership_number'] = $request->membership_number;
        }

        $userData['membership_date'] = $request->membership_date ?? now();
    } else { // treasurer
        // Auto-generate employee ID if not provided
        if (empty($request->employee_id)) {
            $userData['employee_id'] = $this->generateEmployeeId();
        } else {
            $userData['employee_id'] = $request->employee_id;
        }

        $userData['employment_date'] = $request->employment_date ?? now();
        $userData['position'] = $request->position;
        // Branch field removed completely
    }

    // Handle profile photo upload
    if ($request->hasFile('profile_photo')) {
        $path = $request->file('profile_photo')->store('profile-photos', 'public');
        $userData['profile_photo'] = $path;
    }

    $user = User::create($userData);

    // Assign the selected role
    $user->assignRole($request->role);

    // Prepare success message with generated ID
    $identifier = $request->role === 'member' ? $user->membership_number : $user->employee_id;
    $roleName = $request->role === 'member' ? 'Member' : 'Treasurer';

    return redirect()->route('admin.users.index')
        ->with('success', "{$roleName} created successfully with ID: {$identifier}");
}

    public function show(User $user)
{
    // Load relationships correctly
    $user->load([
        'savingsTransactions',
        'loanApplications' => function($query) {
            $query->latest();
        }
    ]);

    // Get loans where this user is a guarantor (if you have that relationship)
    // If you don't have user_id in loan_guarantors, this will be empty for now
    $guaranteedLoans = collect(); // Empty collection until you add user_id to loan_guarantors

    $totalSavings = $user->total_savings;
    $activeLoans = $user->loanApplications()
        ->whereIn('status', ['approved', 'disbursed'])
        ->count();
    $totalLoans = $user->loanApplications()->count();
    $totalGuaranteed = $user->guarantorLoans ? $user->guarantorLoans()->count() : 0;

    return view('admin.users.show', compact(
        'user',
        'totalSavings',
        'activeLoans',
        'totalLoans',
        'totalGuaranteed',
        'guaranteedLoans'
    ));
}

public function edit(User $user)
{
    // Calculate active loans for the user
    $activeLoans = $user->loanApplications()
        ->whereIn('status', ['approved', 'disbursed'])
        ->count();

    return view('admin.users.edit', compact('user', 'activeLoans'));
}

public function update(Request $request, User $user)
{
    $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        'phone_number' => 'required|string|max:20',
        'address' => 'required|string',
        'national_id' => 'required|string|unique:users,national_id,' . $user->id,
        'status' => 'required|in:active,inactive,suspended,pending',
        'next_of_kin_name' => 'required|string|max:255', // Added for all users
        'next_of_kin_phone' => 'required|string|max:20', // Added for all users
        'next_of_kin_relationship' => 'required|string|max:100', // Added for all users
    ];

    // Add role-specific validation rules
    if ($user->hasRole('member')) {
        $rules['membership_number'] = 'nullable|string|unique:users,membership_number,' . $user->id;
        $rules['membership_date'] = 'nullable|date';
    } elseif ($user->hasRole('treasurer')) {
        $rules['employee_id'] = 'nullable|string|unique:users,employee_id,' . $user->id; // Made nullable
        $rules['employment_date'] = 'nullable|date';
        $rules['position'] = 'nullable|string|max:255';
        // Removed branch field
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
        'status' => $request->status,
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

    // Handle password update
    if ($request->filled('password')) {
        $request->validate([
            'password' => 'min:8|confirmed'
        ]);
        $updateData['password'] = Hash::make($request->password);
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

    return redirect()->route('admin.users.index')
        ->with('success', 'User updated successfully.');
}

    public function destroy(User $user)
    {
        // Check if user has active loans
        $activeLoans = $user->loanApplications()
            ->whereIn('status', ['approved', 'disbursed'])
            ->exists();

        if ($activeLoans) {
            return back()->with('error', 'Cannot delete user with active loans.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function toggleStatus(User $user)
    {
        $statusMap = [
            'active' => 'inactive',
            'inactive' => 'active',
            'suspended' => 'active',
            'pending' => 'active'
        ];

        $user->status = $statusMap[$user->status] ?? 'active';
        $user->save();

        return back()->with('success', 'User status updated successfully.');
    }

    // In UserController.php, add these methods:

/**
 * Generate next membership number
 */
private function generateMembershipNumber()
{
    $lastMember = User::role('member')
        ->where('membership_number', 'LIKE', 'MEM-' . date('Y') . '-%')
        ->orderBy('id', 'desc')
        ->first();

    if ($lastMember && preg_match('/MEM-\d+-(\d+)/', $lastMember->membership_number, $matches)) {
        $nextNumber = intval($matches[1]) + 1;
    } else {
        $nextNumber = 1;
    }

    return 'MEM-' . date('Y') . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
}

/**
 * Generate next employee ID
 */
private function generateEmployeeId()
{
    $lastEmployee = User::role('treasurer')
        ->where('employee_id', 'LIKE', 'EMP-' . date('Y') . '-%')
        ->orderBy('id', 'desc')
        ->first();

    if ($lastEmployee && preg_match('/EMP-\d+-(\d+)/', $lastEmployee->employee_id, $matches)) {
        $nextNumber = intval($matches[1]) + 1;
    } else {
        $nextNumber = 1;
    }

    return 'EMP-' . date('Y') . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
}
}
