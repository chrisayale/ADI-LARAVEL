<?php
// app/Http/Controllers/Treasurer/LoanVerificationController.php

namespace App\Http\Controllers\Treasurer;

use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use App\Models\LoanGuarantor;
use App\Models\LoanDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class LoanVerificationController extends Controller
{
    /**
     * Display pending verifications for the treasurer
     */
    public function pending(Request $request)
    {
        $treasurerId = auth()->id();

        $query = LoanApplication::with(['user', 'guarantors', 'documents'])
            ->where('treasurer_id', $treasurerId)
            ->where('status', 'under_verification');

        // Search by member
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('membership_number', 'like', "%{$search}%");
            });
        }

        $loans = $query->latest()->paginate(15);

        // Get counts for summary
        $counts = [
            'total' => LoanApplication::where('treasurer_id', $treasurerId)
                ->where('status', 'under_verification')
                ->count(),
            'with_collateral' => LoanApplication::where('treasurer_id', $treasurerId)
                ->where('status', 'under_verification')
                ->where('has_collateral', true)
                ->count(),
            'with_guarantors' => LoanApplication::where('treasurer_id', $treasurerId)
                ->where('status', 'under_verification')
                ->whereHas('guarantors')
                ->count(),
        ];

        return view('treasurer.verifications.pending', compact('loans', 'counts'));
    }

    /**
     * Display verified loans
     */
    public function verified(Request $request)
    {
        $treasurerId = auth()->id();

        $query = LoanApplication::with(['user', 'verifier'])
            ->where('treasurer_id', $treasurerId)
            ->where('status', 'verified');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('membership_number', 'like', "%{$search}%");
            });
        }

        $loans = $query->latest()->paginate(15);

        return view('treasurer.verifications.verified', compact('loans'));
    }

    /**
     * Show loan verification details
     */
    public function show(LoanApplication $loan)
    {
        // Ensure this loan is assigned to the current treasurer
        if ($loan->treasurer_id != auth()->id()) {
            abort(403, 'This loan is not assigned to you.');
        }

        // Ensure the loan is in under_verification status
        if ($loan->status != 'under_verification') {
            return redirect()->route('treasurer.verifications.pending')
                ->with('error', 'This loan is not pending verification.');
        }

        $loan->load([
            'user',
            'guarantors',
            'documents' => function($query) {
                $query->latest();
            },
            'documents.uploader',
            'documents.guarantor'
        ]);

        return view('treasurer.verifications.show', compact('loan'));
    }

    /**
     * Verify a loan application
     */
    public function verify(Request $request, LoanApplication $loan)
    {
        // Ensure this loan is assigned to the current treasurer
        if ($loan->treasurer_id != auth()->id()) {
            return redirect()->back()
                ->with('error', 'This loan is not assigned to you.');
        }

        $validator = Validator::make($request->all(), [
            'verification_notes' => 'required|string|max:1000',
            'guarantors_verified' => 'required|boolean',
            'collateral_verified' => 'required|boolean',
            'documents_verified' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            // Update guarantor statuses
            if ($request->guarantors_verified) {
                $loan->guarantors()->update([
                    'status' => 'approved',
                    'verified_by' => auth()->id(),
                    'verified_at' => now(),
                    'verification_notes' => $request->verification_notes
                ]);
            }

            // Update document statuses
            if ($request->documents_verified) {
                $loan->documents()->update([
                    'verification_status' => 'verified',
                    'verified_by' => auth()->id(),
                    'verified_at' => now()
                ]);
            }

            // Update loan status
            $loan->update([
                'status' => 'verified',
                'verification_notes' => $request->verification_notes,
                'verified_by' => auth()->id(),
                'verified_at' => now()
            ]);

            DB::commit();

            return redirect()->route('treasurer.verifications.pending')
                ->with('success', 'Loan verified successfully and sent for admin approval.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Loan verification error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Failed to verify loan. Please try again.')
                ->withInput();
        }
    }

    /**
     * Reject a loan application (send back to admin)
     */
    public function reject(Request $request, LoanApplication $loan)
    {
        // Ensure this loan is assigned to the current treasurer
        if ($loan->treasurer_id != auth()->id()) {
            return redirect()->back()
                ->with('error', 'This loan is not assigned to you.');
        }

        $validator = Validator::make($request->all(), [
            'rejection_reason' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            // Update loan status back to pending with notes
            $loan->update([
                'status' => 'pending',
                'verification_notes' => 'Rejected by treasurer: ' . $request->rejection_reason,
                'treasurer_id' => null, // Unassign treasurer
                'verified_at' => null
            ]);

            DB::commit();

            return redirect()->route('treasurer.verifications.pending')
                ->with('success', 'Loan rejected and returned to admin.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Loan rejection error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Failed to reject loan. Please try again.')
                ->withInput();
        }
    }

    /**
     * Verify individual guarantor
     */
    public function verifyGuarantor(Request $request, LoanGuarantor $guarantor)
    {
        $loan = $guarantor->loanApplication;

        // Ensure this loan is assigned to the current treasurer
        if ($loan->treasurer_id != auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:approved,rejected',
            'verification_notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $guarantor->update([
            'status' => $request->status,
            'verification_notes' => $request->verification_notes,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Guarantor ' . $request->status . ' successfully.',
            'status' => $request->status,
            'status_badge' => $request->status == 'approved' ? 'success' : 'danger'
        ]);
    }

    /**
     * Verify individual document
     */
    public function verifyDocument(Request $request, LoanDocument $document)
    {
        $loan = $document->loanApplication;

        // Ensure this loan is assigned to the current treasurer
        if ($loan->treasurer_id != auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'verification_status' => 'required|in:verified,rejected',
            'verification_notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $document->update([
            'verification_status' => $request->verification_status,
            'verification_notes' => $request->verification_notes,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Document ' . $request->verification_status . ' successfully.',
            'status' => $request->verification_status
        ]);
    }
}
