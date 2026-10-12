<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sacco;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class SaccoController extends Controller
{
    /**
     * Display a listing of the sacos.
     */
    public function index(Request $request)
    {
        try {
            $query = Sacco::query();

            // Search functionality
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%")
                      ->orWhere('registration_number', 'like', "%{$search}%");
                });
            }

            // Filter by status
            if ($request->has('status') && $request->status != 'all') {
                $query->where('status', $request->status);
            }

            $sacos = $query->latest()->paginate(15);

            return view('admin.sacos.index', compact('sacos'));
        } catch (\Throwable $e) {
            // Log the error for debugging
            \Log::error('Error in SaccoController@index: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);

            // Show a friendly error message to the user
            return back()
                ->withInput()
                ->with('error', 'An error occurred while loading SACCOs. Please try again.');
        }
    }

    /**
     * Show the form to create a new SACCO.
     */
    public function create()
    {
        return view('admin.sacos.create');
    }

    /**
     * Store a newly created SACCO in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:sacos',
            'registration_number' => 'nullable|string|max:100|unique:sacos',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $input = $request->all();
        // Map phone_number from form to phone column in database
        if (isset($input['phone_number'])) {
            $input['phone'] = $input['phone_number'];
            unset($input['phone_number']);
        }
        Sacco::create($input);

        return redirect()->route('admin.sacos.index')
            ->with('success', 'SACCO created successfully.');
    }

    /**
     * Display the specified SACCO.
     */
    public function show(Sacco $sacco)
    {
        return view('admin.sacos.show', compact('sacco'));
    }

    /**
     * Show the form to edit the specified SACCO.
     */
    public function edit(Sacco $sacco)
    {
        return view('admin.sacos.edit', compact('sacco'));
    }

    /**
     * Update the specified SACCO in storage.
     */
    public function update(Request $request, Sacco $sacco)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:sacos,code,' . $sacco->id,
            'registration_number' => 'nullable|string|max:100|unique:sacos,registration_number,' . $sacco->id,
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $input = $request->all();
        // Map phone_number from form to phone column in database
        if (isset($input['phone_number'])) {
            $input['phone'] = $input['phone_number'];
            unset($input['phone_number']);
        }
        $sacco->update($input);

        return redirect()->route('admin.sacos.index')
            ->with('success', 'SACCO updated successfully.');
    }

    /**
     * Remove the specified SACCO from storage.
     */
    public function destroy(Sacco $sacco)
    {
        // Check if SACCO has users (optional, depending on business rules)
        if ($sacco->users()->exists()) {
            return back()->with('error', 'Cannot delete SACCO that has users assigned.');
        }

        $sacco->delete();

        return redirect()->route('admin.sacos.index')
            ->with('success', 'SACCO deleted successfully.');
    }

    /**
     * Toggle the official account status for a SACCO.
     */
    public function toggleOfficialAccount(Sacco $sacco)
    {
        // Find the official user account for this SACCO
        $officialUser = \App\Models\User::where('sacco_id', $sacco->id)
            ->where('name', 'Compte Officiel - ' . $sacco->name)
            ->first();

        if (!$officialUser) {
            return back()->with('error', 'Compte officiel non trouvé pour ce SACCO.');
        }

        // Toggle the status
        $officialUser->status = ($officialUser->status === 'active') ? 'inactive' : 'active';
        $officialUser->save();

        $statusText = $officialUser->status === 'active' ? 'activé' : 'désactivé';

        return redirect()->route('admin.sacos.show', $sacco->id)
            ->with('success', "Le compte officiel a été $statusText avec succès.");
    }

    /**
     * Toggle the SACCO status.
     */
    public function toggleStatus(Sacco $sacco)
    {
        try {
            // Toggle the status between active and inactive
            $sacco->status = ($sacco->status === 'active') ? 'inactive' : 'active';
            $sacco->save();

            $statusText = $sacco->status === 'active' ? 'activé' : 'désactivé';

            return redirect()->route('admin.sacos.index')
                ->with('success', "Le SACCO a été $statusText avec succès.");
        } catch (\Throwable $e) {
            Log::error('Error toggling SACCO status: ' . $e->getMessage(), [
                'sacco_id' => $sacco->id,
                'exception' => $e
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la mise à jour du statut.');
        }
    }

    /**
     * Show the form for editing the official account of a SACCO.
     */
    public function editOfficialAccount(Sacco $sacco)
    {
        // Find the official user account for this SACCO
        // First search by sacco_id
        $officialUser = User::where('sacco_id', $sacco->id)->first();

        // If not found, search by email
        if (!$officialUser) {
            $officialUser = User::where('email', $sacco->official_email)->first();
        }

        // If still no official account exists, create one automatically
        if (!$officialUser) {
            $officialUser = User::create([
                'name' => 'Compte Officiel - ' . $sacco->name,
                'email' => $sacco->official_email,
                'password' => Hash::make('avec1234'), // Default password
                'sacco_id' => $sacco->id,
                'status' => 'active',
            ]);
        }

        // Return the view with both sacco and officialUser variables
        return view('admin.sacos.official-account', compact('sacco', 'officialUser'));
    }

    /**
     * Update the official account for a SACCO.
     */
    public function updateOfficialAccount(Request $request, Sacco $sacco)
    {
        // Validate the request
        $validated = $request->validate([
            'email' => 'required|email|max:255|regex:/@adi\\.com$/|unique:users,email,' . ($officialUserId = $this->getOfficialUserId($sacco)) . ',id',
            'password' => 'nullable|min:6|confirmed',
            'status' => 'in:active,inactive',
        ]);

        // Find the official user account for this SACCO
        $officialUser = User::where('sacco_id', $sacco->id)
            ->where('name', 'Compte Officiel - ' . $sacco->name)
            ->first();

        if (!$officialUser) {
            // If no official account exists, create one (should not happen in normal flow)
            $officialUser = User::create([
                'name' => 'Compte Officiel - ' . $sacco->name,
                'email' => $sacco->email, // Use current SACCO email as fallback
                'password' => Hash::make('avec1234'), // Default password
                'sacco_id' => $sacco->id,
                'status' => 'active',
            ]);
        }

        // Update the official account
        $officialUser->email = $validated['email'];
        if (isset($validated['password'])) {
            $officialUser->password = Hash::make($validated['password']);
        }
        if (isset($validated['status'])) {
            $officialUser->status = $validated['status'];
        }
        $officialUser->save();

        // Also update the SACCO's email to match the official account email
        $sacco->email = $validated['email'];
        $sacco->save();

        return redirect()->route('admin.sacos.official-account', $sacco->id)
            ->with('success', 'Compte officiel mis à jour avec succès.');
    }

    /**
     * Helper method to get the official user ID for a SACCO.
     */
    private function getOfficialUserId(Sacco $sacco)
    {
        $officialUser = User::where('sacco_id', $sacco->id)
            ->where('name', 'Compte Officiel - ' . $sacco->name)
            ->first();

        return $officialUser ? $officialUser->id : null;
    }
}