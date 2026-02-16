<?php
// app/Http/Controllers/Member/LoanApplicationController.php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use App\Models\LoanGuarantor;
use App\Models\LoanDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class LoanApplicationController extends Controller
{
    /**
     * Show loan application form
     */
    public function create()
    {
        return view('member.loans.apply');
    }

    /**
     * Store loan application
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount_applied' => 'required|numeric|min:10000|max:10000000',
            'purpose' => 'required|string|max:1000',
            'has_collateral' => 'boolean',
            'collateral_type' => 'required_if:has_collateral,true|string|max:100',
            'collateral_description' => 'required_if:has_collateral,true|string',
            'collateral_value' => 'required_if:has_collateral,true|numeric|min:1',
            'guarantors' => 'required|array|min:1|max:3',
            'guarantors.*.name' => 'required|string|max:255',
            'guarantors.*.phone' => 'required|string|max:20',
            'guarantors.*.email' => 'nullable|email|max:255',
            'guarantors.*.id_number' => 'required|string|max:50',
            'guarantors.*.occupation' => 'nullable|string|max:100',
            'guarantors.*.address' => 'nullable|string',
            'guarantors.*.relationship' => 'nullable|string|max:100',
            'documents' => 'nullable|array|max:5',
            'documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            // Create loan application
            $loan = LoanApplication::create([
                'user_id' => auth()->id(),
                'application_number' => 'LN-' . date('Y') . '-' . str_pad(LoanApplication::max('id') + 1, 5, '0', STR_PAD_LEFT),
                'amount_applied' => $request->amount_applied,
                'purpose' => $request->purpose,
                'has_collateral' => $request->has_collateral ?? false,
                'collateral_type' => $request->collateral_type,
                'collateral_description' => $request->collateral_description,
                'collateral_value' => $request->collateral_value,
                'status' => 'pending'
            ]);

            // Add guarantors
            foreach ($request->guarantors as $guarantorData) {
                $loan->guarantors()->create([
                    'name' => $guarantorData['name'],
                    'phone' => $guarantorData['phone'],
                    'email' => $guarantorData['email'] ?? null,
                    'id_number' => $guarantorData['id_number'],
                    'occupation' => $guarantorData['occupation'] ?? null,
                    'address' => $guarantorData['address'] ?? null,
                    'relationship' => $guarantorData['relationship'] ?? null,
                    'status' => 'pending'
                ]);
            }

            // Upload documents
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $index => $document) {
                    $originalName = $document->getClientOriginalName();
                    $fileType = strtolower($document->getClientOriginalExtension());
                    $fileName = time() . '_' . $index . '_' . uniqid() . '.' . $fileType;

                    $path = $document->storeAs('loan-documents/' . $loan->id, $fileName, 'public');

                    LoanDocument::create([
                        'loan_application_id' => $loan->id,
                        'uploaded_by' => auth()->id(),
                        'document_name' => $originalName,
                        'original_name' => $originalName,
                        'file_path' => $path,
                        'file_type' => $fileType,
                        'file_size' => $document->getSize(),
                        'mime_type' => $document->getMimeType(),
                        'category' => 'application_form',
                        'verification_status' => 'pending'
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('member.loans.applications')
                ->with('success', 'Loan application submitted successfully. Reference: ' . $loan->application_number);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Loan application error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Failed to submit loan application. Please try again.')
                ->withInput();
        }
    }

    /**
     * List member's loan applications
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = $user->loanApplications()->with(['guarantors']);

        // Filter by status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        $applications = $query->latest()->paginate(15)->withQueryString();

        // Summary counts
        $counts = [
            'pending' => $user->loanApplications()->where('status', 'pending')->count(),
            'approved' => $user->loanApplications()->where('status', 'approved')->count(),
            'disbursed' => $user->loanApplications()->where('status', 'disbursed')->count(),
            'completed' => $user->loanApplications()->where('status', 'completed')->count(),
        ];

        return view('member.loans.applications', compact('applications', 'counts'));
    }

    /**
     * Show member's active loans
     */
    public function myLoans(Request $request)
    {
        $user = auth()->user();

        $query = $user->loanApplications()
            ->whereIn('status', ['approved', 'disbursed', 'completed']);

        $loans = $query->latest()->paginate(15);

        return view('member.loans.my-loans', compact('loans'));
    }

    /**
     * Show single loan application
     */
    public function show(LoanApplication $loan)
    {
        // Ensure this loan belongs to the authenticated user
        if ($loan->user_id != auth()->id()) {
            abort(403, 'Unauthorized access to this loan.');
        }

        $loan->load([
            'guarantors',
            'repayments' => function($query) {
                $query->orderBy('installment_number');
            },
            'documents'
        ]);

        return view('member.loans.show', compact('loan'));
    }

    /**
     * Show repayment schedule
     */
    public function repaymentSchedule(LoanApplication $loan)
    {
        // Ensure this loan belongs to the authenticated user
        if ($loan->user_id != auth()->id()) {
            abort(403, 'Unauthorized access to this loan.');
        }

        $loan->load([
            'repayments' => function($query) {
                $query->orderBy('installment_number');
            }
        ]);

        $totalPaid = $loan->repayments->sum('amount_paid');
        $totalDue = $loan->repayments->sum('amount_due');
        $nextPayment = $loan->repayments()
            ->whereIn('status', ['pending', 'partial'])
            ->orderBy('due_date')
            ->first();

        return view('member.loans.repayment-schedule', compact('loan', 'totalPaid', 'totalDue', 'nextPayment'));
    }
}
