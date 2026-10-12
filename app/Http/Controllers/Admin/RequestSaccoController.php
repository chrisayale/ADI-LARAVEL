<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RequestSacco;
use App\Models\Sacco;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RequestSaccoController extends Controller
{
    /**
     * Display a listing of SACCO requests.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Only Super Admin can access
        if (!Auth::user()->hasRole('super_admin')) {
            abort(403, 'Only a Super Admin can access this page.');
        }

        $status = $request->input('status', 'pending');
        $query = RequestSacco::with('requester');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.sacco-requests.index', compact('requests', 'status'));
    }

    /**
     * Show the form for viewing a specific SACCO request.
     *
     * @param  \App\Models\RequestSacco  $requestSacco
     * @return \Illuminate\View\View
     */
    public function show(RequestSacco $requestSacco)
    {
        // Only Super Admin can access
        if (!Auth::user()->hasRole('super_admin')) {
            abort(403, 'Only a Super Admin can access this page.');
        }

        return view('admin.sacco-requests.show', compact('requestSacco'));
    }

    /**
     * Show the form for approving a SACCO request.
     *
     * @param  \App\Models\RequestSacco  $requestSacco
     * @return \Illuminate\View\View
     */
    public function approve(RequestSacco $requestSacco)
    {
        // Only Super Admin can access
        if (!Auth::user()->hasRole('super_admin')) {
            abort(403, 'Only a Super Admin can access this page.');
        }

        return view('admin.sacco-requests.approve', compact('requestSacco'));
    }

    /**
     * Approve a SACCO request and create the actual SACCO.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RequestSacco  $requestSacco
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeApproval(Request $request, RequestSacco $requestSacco)
    {
        // Only Super Admin can access
        if (!Auth::user()->hasRole('super_admin')) {
            abort(403, 'Only a Super Admin can access this page.');
        }

        $validator = Validator::make($request->all(), [
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        // Generate unique SACCO code
        $code = $this->generateUniqueSaccoCode();

        // Generate official email for the SACCO
        $officialEmail = $this->generateOfficialEmail($requestSacco->name);

        // Create the actual SACCO with the official email
        $sacco = Sacco::create([
            'name' => $requestSacco->name,
            'code' => $code,
            'registration_number' => $requestSacco->registration_number,
            'address' => $requestSacco->address,
            'phone' => $requestSacco->phone_number, // Note: request uses phone_number, db uses phone
            'email' => $officialEmail,
            'primary_currency' => 'USD', // Default values
            'secondary_currency' => 'CDF', // Default values
            'status' => 'active',
            'settings' => $requestSacco->settings,
        ]);

        // Create official user/account for the SACCO
        User::create([
            'name' => 'Compte Officiel - ' . $requestSacco->name,
            'email' => $officialEmail,
            'password' => Hash::make('avec1234'), // Default password
            'sacco_id' => $sacco->id,
            'status' => 'active',
        ]);

        // Update the request as approved
        $requestSacco->update([
            'status' => 'approved',
            'code' => $code,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        return Redirect::route('super-admin.request-sacco.index')
            ->with('success', "La demande de SACCO {$requestSacco->name} a été approuvée et le SACCO officiel a été créé avec le code {$code} et l'e-mail officiel {$officialEmail}.");
    }

    /**
     * Show the form for rejecting a SACCO request.
     *
     * @param  \App\Models\RequestSacco  $requestSacco
     * @return \Illuminate\View\View
     */
    public function reject(RequestSacco $requestSacco)
    {
        // Only Super Admin can access
        if (!Auth::user()->hasRole('super_admin')) {
            abort(403, 'Only a Super Admin can access this page.');
        }

        return view('admin.sacco-requests.reject', compact('requestSacco'));
    }

    /**
     * Reject a SACCO request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RequestSacco  $requestSacco
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeRejection(Request $request, RequestSacco $requestSacco)
    {
        // Only Super Admin can access
        if (!Auth::user()->hasRole('super_admin')) {
            abort(403, 'Only a Super Admin can access this page.');
        }

        $validator = Validator::make($request->all(), [
            'rejection_reason' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        // Update the request as rejected
        $requestSacco->update([
            'status' => 'rejected',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        return Redirect::route('super-admin.request-sacco.index')
            ->with('success', "La demande de SACCO {$requestSacco->name} a été rejetée.");
    }

    /**
     * Generate a unique SACCO code.
     *
     * @return string
     */
    private function generateUniqueSaccoCode()
    {
        do {
            $code = '09' . str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
        } while (Sacco::where('code', $code)->exists());

        return $code;
    }

    /**
     * Generate an official email for a SACCO based on its name.
     *
     * @param  string  $name
     * @return string
     */
    private function generateOfficialEmail($name)
    {
        // Remove accents, convert to lowercase, keep only alphanumeric
        $cleanName = preg_replace('/[^a-z0-9]/i', '', iconv('UTF-8', 'ASCII//TRANSLIT', strtolower($name)));

        $baseEmail = $cleanName . '@adi.com';
        $email = $baseEmail;
        $counter = 1;

        // Check if email already exists in users table
        while (User::where('email', $email)->exists()) {
            $email = $cleanName . $counter . '@adi.com';
            $counter++;
        }

        return $email;
    }
}
