<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Sacco;
use App\Models\User;
use Illuminate\Http\Request;
use App\Support\TenantContext;
use App\Services\TenantResolver;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SaccoController extends Controller
{
    /**
     * Display a listing of all SACCOs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Check if the user is a Super Admin
        if (!Gate::forUser($request->user())->allows('viewAny', Sacco::class)) {
            abort(403, 'Unauthorized action.');
        }

        // Get all SACCOs with pagination
        $sacos = Sacco::orderBy('name')->paginate(10);

        return view('super-admin.sacos.index', compact('sacos'));
    }

    /**
     * Show the form for creating a new SACCO.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // Check if the user is a Super Admin
        if (!Gate::forUser($request->user())->allows('create', Sacco::class)) {
            abort(403, 'Unauthorized action.');
        }

        return view('super-admin.sacos.create');
    }

    /**
     * Store a newly created SACCO in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Check if the user is a Super Admin
        if (!Gate::forUser($request->user())->allows('create', Sacco::class)) {
            abort(403, 'Unauthorized action.');
        }

        // Validate the request fields matching the request sacco form
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'province' => 'required|string|max:100',
            'territory' => 'required|string|max:100',
            'city_village' => 'required|string|max:100',
            'address' => 'nullable|string',
            'applicant_name' => 'required|string|max:255',
            'applicant_role' => 'required|string|max:100',
            'applicant_email' => 'nullable|email|max:255',
            'applicant_phone' => 'required|string|max:20',
            'description' => 'nullable|string',
        ]);

        // Generate a unique SACCO code
        $code = $this->generateUniqueSaccoCode();

        // Prepare settings array with extra fields
        $settings = [
            'province' => $request->province,
            'territory' => $request->territory,
            'city_village' => $request->city_village,
            'applicant_name' => $request->applicant_name,
            'applicant_role' => $request->applicant_role,
            'applicant_email' => $request->applicant_email,
            'applicant_phone' => $request->applicant_phone,
            'description' => $request->description,
        ];

        // Create the SACCO with mapped fields and defaults
        $sacco = Sacco::create([
            'name' => $request->name,
            'code' => $code,
            'registration_number' => null, // Not provided in request form
            'phone' => $request->phone_number,
            'email' => $request->email,
            'address' => $request->address,
            'primary_currency' => 'USD', // Default values
            'secondary_currency' => 'CDF', // Default values
            'status' => 'active', // Default status
            'settings' => $settings,
        ]);

        // Redirect with success message
        return Redirect::route('super-admin.sacos.index')
            ->with('success', 'SACCO créé avec succès.');
    }

    /**
     * Display the specified SACCO.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sacco  $sacco
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Sacco $sacco)
    {
        // Check if the user is a Super Admin
        if (!Gate::forUser($request->user())->allows('view', $sacco)) {
            abort(403, 'Unauthorized action.');
        }

        return view('super-admin.sacos.show', compact('sacco'));
    }

    /**
     * Show the form for editing the specified SACCO.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sacco  $sacco
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Sacco $sacco)
    {
        // Check if the user is a Super Admin
        if (!Gate::forUser($request->user())->allows('update', $sacco)) {
            abort(403, 'Unauthorized action.');
        }

        return view('super-admin.sacos.edit', compact('sacco'));
    }

    /**
     * Update the specified SACCO in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sacco  $sacco
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sacco $sacco)
    {
        // Check if the user is a Super Admin
        if (!Gate::forUser($request->user())->allows('update', $sacco)) {
            abort(403, 'Unauthorized action.');
        }

        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:sacos,code,'.$sacco->id,
            'registration_number' => 'nullable|string|max:100|unique:sacos,registration_number,'.$sacco->id,
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'primary_currency' => 'required|string|max:3',
            'secondary_currency' => 'required|string|max:3',
            'status' => 'required|in:active,inactive,suspended',
            'settings' => 'nullable|array',
        ]);

        // Update the SACCO
        $sacco->update($validated);

        // Redirect with success message
        return Redirect::route('super-admin.sacos.index')
            ->with('success', 'SACCO actualizado con éxito.');
    }

    /**
     * Remove the specified SACCO from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sacco  $sacco
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Sacco $sacco)
    {
        // Check if the user is a Super Admin
        if (!Gate::forUser($request->user())->allows('delete', $sacco)) {
            abort(403, 'Unauthorized action.');
        }

        // Delete the SACCO
        $sacco->delete();

        // Redirect with success message
        return Redirect::route('super-admin.sacos.index')
            ->with('success', 'SACCO eliminado con éxito.');
    }

    /**
     * Set the SACCO context for Super Admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Services\TenantResolver  $tenantResolver
     * @return \Illuminate\Http\Response
     */
    public function setContext(Request $request, TenantResolver $tenantResolver)
    {
        // Check if the user is a Super Admin
        if (!Gate::forUser($request->user())->allows('viewAny', Sacco::class)) {
            abort(403, 'Unauthorized action.');
        }

        // Validate the request
        $validated = $request->validate([
            'sacco_id' => 'required|integer|exists:sacos,id',
        ]);

        try {
            // Set the selected SACCO for the Super Admin
            $tenantResolver->setSelectedSacco($validated['sacco_id']);

            // Redirect back with success message
            return Redirect::back()
                ->with('success', 'Contexto SACCO cambiado con éxito.');
        } catch (\Exception $e) {
            // Redirect back with error message
            return Redirect::back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Clear the SACCO context for Super Admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Services\TenantResolver  $tenantResolver
     * @return \Illuminate\Http\Response
     */
    public function clearContext(Request $request, TenantResolver $tenantResolver)
    {
        // Check if the user is a Super Admin
        if (!Gate::forUser($request->user())->allows('viewAny', Sacco::class)) {
            abort(403, 'Unauthorized action.');
        }

        // Clear the selected SACCO for the Super Admin
        $tenantResolver->clearSelectedSacco();

        // Redirect back with success message
        return Redirect::back()
            ->with('success', 'Contexto SACCO eliminado con éxito.');
    }

    /**
     * Toggle the SACCO status.
     */
    public function toggleStatus(Request $request, Sacco $sacco)
    {
        try {
            // Check if the user is a Super Admin
            if (!Gate::forUser($request->user())->allows('update', $sacco)) {
                abort(403, 'Unauthorized action.');
            }

            // Toggle the status between active and inactive
            $sacco->status = ($sacco->status === 'active') ? 'inactive' : 'active';
            $sacco->save();

            $statusText = $sacco->status === 'active' ? 'activado' : 'desactivado';

            return Redirect::route('super-admin.sacos.index')
                ->with('success', "El SACCO ha sido $statusText con éxito.");
        } catch (\Throwable $e) {
            Log::error('Error toggling SACCO status: ' . $e->getMessage(), [
                'sacco_id' => $sacco->id,
                'exception' => $e
            ]);

            return Redirect::back()
                ->with('error', 'Ha ocurrido un error al actualizar el estado.');
        }
    }

    /**
     * Show the form for editing the official account of a SACCO.
     */
    public function editOfficialAccount(Request $request, Sacco $sacco)
    {
        // Find the official user account for this SACCO
        $officialUser = User::where('sacco_id', $sacco->id)
            ->where('name', 'Compte Officiel - ' . $sacco->name)
            ->first();

        // If no official account exists, we'll handle this in the view
        // but we should ideally create one or show an error

        return view('super-admin.sacos.official-account', compact('sacco', 'officialUser'));
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

        return Redirect::route('super-admin.sacos.official-account', $sacco->id)
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

    /**
     * Generate a unique SACCO code.
     *
     * @return string
     */
    private function generateUniqueSaccoCode()
    {
        do {
            $year = date('Y');
            $random = strtoupper(Str::random(3));
            $code = "SC-{$year}-{$random}";
        } while (Sacco::where('code', $code)->exists());

        return $code;
    }
}