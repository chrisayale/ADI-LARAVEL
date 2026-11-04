<?php
// app/Http/Controllers/Member/LoanApplicationController.php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLoanApplicationRequest;
use App\Models\LoanApplication;
use App\Models\LoanGuarantor;
use App\Models\LoanDocument;
use App\Models\Sacco;
use Illuminate\Support\Facades\DB;
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
    public function store(StoreLoanApplicationRequest $request)
    {
        DB::beginTransaction();

        try {
            // Ensure application_number is never passed from request - let model generate it
            $data = $request->validated();
            unset($data['application_number']);

            // Force l'ajout de l'utilisateur connecté (member crée pour lui-même)
            $data['user_id'] = auth()->id();

            // Get or create the default SACCO "KANZANZA FINANCE"
            $sacco = Sacco::firstOrCreate(
                ['name' => 'KANZANZA FINANCE'],
                [
                    'code' => 'KZF',
                    'registration_number' => 'KZF-' . strtoupper(\Illuminate\Support\Str::random(10)),
                    'status' => 'active',
                    'primary_currency' => 'USD',
                    'secondary_currency' => 'CDF',
                ]
            );

            // Determine sacco_id: use user's sacco_id if available, otherwise use default SACCO
            $saccoId = auth()->user()->sacco_id ?? $sacco->id;

            // Determine collateral values based on checkbox
            $hasCollateral = $request->boolean('has_collateral');
            $collateralType = $hasCollateral ? $request->collateral_type : 'N/A';
            $collateralDescription = $hasCollateral ? $request->collateral_description : 'Aucune garantie';
            $collateralValue = $hasCollateral ? $request->collateral_value : 0;

            // Merge remaining computed fields
            $data = array_merge($data, [
                'sacco_id' => $saccoId,
                'has_collateral' => $hasCollateral,
                'collateral_type' => $collateralType,
                'collateral_description' => $collateralDescription,
                'collateral_value' => $collateralValue,
                'status' => 'pending'
            ]);

            // Create loan application
            $loan = LoanApplication::create($data);

            // Add guarantors
            foreach ($request->guarantors as $guarantorData) {
                $loan->guarantors()->create([
                    'sacco_id' => $saccoId,
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
                        'sacco_id' => $saccoId,
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
