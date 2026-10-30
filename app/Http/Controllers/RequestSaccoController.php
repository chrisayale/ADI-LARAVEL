<?php

namespace App\Http\Controllers;

use App\Models\RequestSacco;
use App\Models\User;
use App\Models\Sacco;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RequestSaccoController extends Controller
{
    /**
     * Show the form to create a new SACCO request.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('request-sacco.create');
    }

    /**
     * Store a newly created SACCO request in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        \Log::info('RequestSacco store method called with input: ', $request->all());

        $validator = Validator::make($request->all(), [
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

        if ($validator->fails()) {
            \Log::warning('RequestSacco validation failed: ', $validator->errors()->toArray());
            return Redirect::back()->withErrors($validator)->withInput();
        }

        \Log::info('Validation passed, creating RequestSacco record.');

        // Generate a unique code for the request
        $code = $this->generateUniqueRequestCode();

        $requestSacco = RequestSacco::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'province' => $request->province,
            'territory' => $request->territory,
            'city_village' => $request->city_village,
            'address' => $request->address,
            'applicant_name' => $request->applicant_name,
            'applicant_role' => $request->applicant_role,
            'applicant_email' => $request->applicant_email,
            'applicant_phone' => $request->applicant_phone,
            'description' => $request->description,
            'status' => 'pending',
            'requested_by' => Auth::id(),
            'code' => $code,
        ]);

        \Log::info('RequestSacco record created with ID: ' . $requestSacco->id . ' and code: ' . $code);

        return Redirect::route('request-sacco.index')
            ->with('success', 'Your request to create a SACCO has been submitted.');
    }

    /**
     * Generate a unique request code.
     *
     * @return string
     */
    private function generateUniqueRequestCode()
    {
        $attempts = 0;
        do {
            // Generate a random 8-character uppercase alphanumeric code
            $code = strtoupper(Str::random(8));
            $attempts++;
            if ($attempts > 50) {
                // Fallback: add timestamp to ensure uniqueness
                $code = strtoupper(Str::random(8)) . time();
                break;
            }
        } while (RequestSacco::where('code', $code)->exists());

        return $code;
    }

    /**
     * Display a listing of pending SACCO requests.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $requests = RequestSacco::where('status', 'pending')
            ->with('requester')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('request-sacco.index', compact('requests'));
    }

    /**
     * Approve a SACCO request and create the actual SACCO.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RequestSacco  $requestSacco
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(Request $request, RequestSacco $requestSacco)
    {
        // Only Super Admin can approve
        if (!Auth::user()->hasRole('super_admin')) {
            abort(403, 'Only a Super Admin can approve a request.');
        }

        // Show approval form
        return view('admin.sacco-requests.approve', compact('requestSacco'));
    }

    /**
     * Store approval of a SACCO request and create the actual SACCO.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RequestSacco  $requestSacco
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeApproval(Request $request, RequestSacco $requestSacco)
    {
        // Only Super Admin can approve
        if (!Auth::user()->hasRole('super_admin')) {
            abort(403, 'Only a Super Admin can approve a request.');
        }

        $validator = Validator::make($request->all(), [
            'code' => 'nullable|string|max:20|unique:sacos,code,' . ($requestSacco->code ?? ''),
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        // Generate SACCO code if not provided
        $saccoCode = $request->code;
        if (!$saccoCode) {
            $attempts = 0;
            do {
                $saccoCode = strtoupper(Str::random(6));
                $attempts++;
                if ($attempts > 50) {
                    // Fallback: add timestamp to ensure uniqueness
                    $saccoCode = strtoupper(Str::random(6)) . time();
                    break;
                }
            } while (Sacco::where('code', $saccoCode)->exists());
        }

        // Create the actual SACCO
        $sacco = Sacco::create([
            'name' => $requestSacco->name,
            'code' => $saccoCode,
            'registration_number' => $requestSacco->registration_number,
            'physical_address' => $requestSacco->address,
            'mobile_money' => $requestSacco->phone_number,
            'official_email' => $requestSacco->email,
            'primary_currency' => 'USD', // Default values
            'secondary_currency' => 'CDF', // Default values
            'status' => 'active',
            'settings' => $requestSacco->settings,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        // Update the request as approved
        $requestSacco->update([
            'status' => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'approval_code' => $saccoCode,
        ]);

        return Redirect::route('admin.request-sacco.index')
            ->with('success', "Le SACCO {$sacco->name} a été créé avec le code {$sacco->code}.");
    }

    /**
     * Reject a SACCO request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RequestSacco  $requestSacco
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Request $request, RequestSacco $requestSacco)
    {
        // Only Super Admin can reject
        if (!Auth::user()->hasRole('super_admin')) {
            abort(403, 'Only a Super Admin can reject a request.');
        }

        // Show rejection form
        return view('admin.sacco-requests.reject', compact('requestSacco'));
    }

    /**
     * Store rejection of a SACCO request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RequestSacco  $requestSacco
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeRejection(Request $request, RequestSacco $requestSacco)
    {
        // Only Super Admin can reject
        if (!Auth::user()->hasRole('super_admin')) {
            abort(403, 'Only a Super Admin can reject a request.');
        }

        $validator = Validator::make($request->all(), [
            'reason' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        // Update the request as rejected
        $requestSacco->update([
            'status' => 'rejected',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'rejection_reason' => $request->reason,
        ]);

        return Redirect::route('admin.request-sacco.index')
            ->with('success', 'La demande a été rejetée.');
    }
}