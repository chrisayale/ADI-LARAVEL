<?php
// app/Http/Controllers/Admin/LoanController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use App\Models\User;
use App\Models\LoanRepayment;
use App\Models\DefaulterTracking;
use App\Models\LoanDocument;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LoanController extends Controller
{
    /**
     * Display dashboard with loan statistics
     */
    public function dashboard()
    {
        $stats = [
            'total_loans' => LoanApplication::count(),
            'pending_loans' => LoanApplication::pending()->count(),
            'under_verification' => LoanApplication::underVerification()->count(),
            'verified_loans' => LoanApplication::verified()->count(),
            'approved_loans' => LoanApplication::approved()->count(),
            'disbursed_loans' => LoanApplication::disbursed()->count(),
            'completed_loans' => LoanApplication::completed()->count(),
            'defaulted_loans' => LoanApplication::defaulted()->count(),

            'total_disbursed' => LoanApplication::disbursed()->sum('amount_approved'),
            'total_repaid' => LoanRepayment::sum('amount_paid'),
            'total_outstanding' => LoanApplication::disbursed()->get()->sum('balance'),

            'pending_amount' => LoanApplication::pending()->sum('amount_applied'),
            'approved_amount' => LoanApplication::approved()->sum('amount_approved'),
        ];

        $recentLoans = LoanApplication::with(['user', 'treasurer'])
            ->latest()
            ->limit(10)
            ->get();

        $defaulters = DefaulterTracking::with(['user', 'loanApplication'])
            ->whereIn('status', ['new', 'follow_up_1', 'follow_up_2', 'final_notice'])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.loans.dashboard', compact('stats', 'recentLoans', 'defaulters'));
    }

    /**
     * Display a listing of loan applications.
     */
    public function index(Request $request)
    {
        $query = LoanApplication::with(['user', 'treasurer', 'verifier', 'approver']);

        // Filter by status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Search by user
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('application_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('membership_number', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by amount range
        if ($request->has('min_amount') && $request->min_amount) {
            $query->where('amount_applied', '>=', $request->min_amount);
        }
        if ($request->has('max_amount') && $request->max_amount) {
            $query->where('amount_applied', '<=', $request->max_amount);
        }

        // Filter by treasurer
        if ($request->has('treasurer_id') && $request->treasurer_id) {
            $query->where('treasurer_id', $request->treasurer_id);
        }

        $loans = $query->latest()->paginate(15)->withQueryString();

        // Get counts for summary cards
        $summary = [
            'pending' => LoanApplication::pending()->count(),
            'under_verification' => LoanApplication::underVerification()->count(),
            'verified' => LoanApplication::verified()->count(),
            'approved' => LoanApplication::approved()->count(),
            'disbursed' => LoanApplication::disbursed()->count(),
            'completed' => LoanApplication::completed()->count(),
            'defaulted' => LoanApplication::defaulted()->count(),
            'total' => LoanApplication::count()
        ];

        // Get active treasurers for filter dropdown
        $treasurers = User::role('treasurer')->where('status', 'active')->get();

        return view('admin.loans.index', compact('loans', 'summary', 'treasurers'));
    }

    /**
     * Display pending loan applications.
     */
    public function pending(Request $request)
    {
        $query = LoanApplication::with(['user'])
            ->where('status', 'pending');

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('membership_number', 'like', "%{$search}%");
            });
        }

        $loans = $query->latest()->paginate(15);

        $totalPending = LoanApplication::pending()->count();
        $totalAmount = LoanApplication::pending()->sum('amount_applied');

        return view('admin.loans.pending', compact('loans', 'totalPending', 'totalAmount'));
    }

    /**
     * Display applications under verification.
     */
    public function underVerification(Request $request)
    {
        $query = LoanApplication::with(['user', 'treasurer'])
            ->where('status', 'under_verification');

        if ($request->has('treasurer_id')) {
            $query->where('treasurer_id', $request->treasurer_id);
        }

        $loans = $query->latest()->paginate(15);

        $treasurers = User::role('treasurer')->where('status', 'active')->get();

        return view('admin.loans.under-verification', compact('loans', 'treasurers'));
    }

    /**
     * Display verified loan applications (ready for admin approval).
     */
    public function verified(Request $request)
    {
        $loans = LoanApplication::with(['user', 'treasurer', 'verifier'])
            ->where('status', 'verified')
            ->latest()
            ->paginate(15);

        return view('admin.loans.verified', compact('loans'));
    }

    /**
     * Display approved loans.
     */
    public function approved(Request $request)
    {
        $query = LoanApplication::with(['user', 'approver'])
            ->where('status', 'approved');

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('membership_number', 'like', "%{$search}%");
            });
        }

        $approvedLoans = $query->latest()->paginate(15);

        // Summary statistics
        $totalApproved = LoanApplication::where('status', 'approved')->count();
        $totalApprovedAmount = LoanApplication::where('status', 'approved')->sum('amount_approved');
        $totalInterest = LoanApplication::where('status', 'approved')
            ->select(DB::raw('SUM(total_repayable - amount_approved) as total'))
            ->first()->total ?? 0;

        return view('admin.loans.approved', compact('approvedLoans', 'totalApproved', 'totalApprovedAmount', 'totalInterest'));
    }

    /**
     * Display disbursed loans.
     */
    public function disbursed(Request $request)
    {
        $query = LoanApplication::with(['user', 'disburser', 'repayments'])
            ->where('status', 'disbursed');

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('membership_number', 'like', "%{$search}%");
            });
        }

        $disbursedLoans = $query->latest()->paginate(15);

        // Calculate totals
        $totalDisbursed = LoanApplication::where('status', 'disbursed')->sum('amount_approved');
        $totalRepaid = LoanRepayment::whereIn('loan_application_id',
            LoanApplication::where('status', 'disbursed')->pluck('id')
        )->sum('amount_paid');
        $outstandingBalance = $totalDisbursed - $totalRepaid;
        $activeLoans = LoanApplication::where('status', 'disbursed')->count();

        return view('admin.loans.disbursed', compact(
            'disbursedLoans',
            'totalDisbursed',
            'totalRepaid',
            'outstandingBalance',
            'activeLoans'
        ));
    }

    /**
     * Display completed loans.
     */
    public function completed(Request $request)
    {
        $completedLoans = LoanApplication::with(['user'])
            ->where('status', 'completed')
            ->latest()
            ->paginate(15);

        $totalCompleted = LoanApplication::where('status', 'completed')->count();
        $totalRepaid = LoanApplication::where('status', 'completed')->sum('total_repayable');

        return view('admin.loans.completed', compact('completedLoans', 'totalCompleted', 'totalRepaid'));
    }

    /**
     * Display defaulters list.
     */
    public function defaulters(Request $request)
    {
        $query = DefaulterTracking::with(['user', 'loanApplication', 'assignedTo']);

        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        $defaulters = $query->latest()->paginate(15);

        // Summary statistics
        $totalDefaulters = DefaulterTracking::count();
        $totalOverdue = DefaulterTracking::sum('amount_overdue');
        $avgOverdueDays = DefaulterTracking::avg('days_overdue') ?? 0;

        $treasurers = User::role('treasurer')->where('status', 'active')->get();

        return view('admin.loans.defaulters', compact(
            'defaulters',
            'totalDefaulters',
            'totalOverdue',
            'avgOverdueDays',
            'treasurers'
        ));
    }

    /**
     * Display the specified loan application.
     */
    public function show(LoanApplication $loan)
    {
        $loan->load([
            'user',
            'treasurer',
            'verifier',
            'approver',
            'disburser',
            'guarantors',
            'repayments' => function($query) {
                $query->orderBy('installment_number');
            },
            'defaulterTracking.assignedTo'
        ]);

        // Get eligible treasurers for assignment
        $treasurers = User::role('treasurer')->where('status', 'active')->get();

        // Calculate repayment statistics
        $totalPaid = $loan->repayments ? $loan->repayments->sum('amount_paid') : 0;
        $totalDue = $loan->repayments ? $loan->repayments->sum('amount_due') : 0;

        $upcomingPayments = collect();
        if ($loan->repayments) {
            $upcomingPayments = $loan->repayments()
                ->whereIn('status', ['pending', 'partial'])
                ->where('due_date', '>=', now())
                ->orderBy('due_date')
                ->limit(5)
                ->get();
        }

        return view('admin.loans.show', compact('loan', 'treasurers', 'totalPaid', 'totalDue', 'upcomingPayments'));
    }

    /**
     * Show form to create new loan application (admin creates for member).
     */
    public function create()
    {
        $members = User::role('member')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('admin.loans.create', compact('members'));
    }

    /**
     * Store a newly created loan application.
     */
   /**
     * Store a newly created loan application.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'amount_applied' => 'required|numeric|min:1|max:10000000',
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
            'documents' => 'nullable|array|max:10',
            'documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:15360', // 15MB in KB
            'document_categories' => 'nullable|array|max:10',
            'document_categories.*' => 'nullable|string|in:guarantor_id,collateral_photo,collateral_document,proof_of_income,bank_statement,application_form,other',
            'document_guarantor' => 'nullable|array|max:10',
            'document_descriptions' => 'nullable|array|max:10',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Create loan application
            $loan = LoanApplication::create([
                'user_id' => $request->user_id,
                'application_number' => 'LN-' . date('Y') . '-' . str_pad(LoanApplication::max('id') + 1, 5, '0', STR_PAD_LEFT),
                'amount_applied' => $request->amount_applied,
                'purpose' => $request->purpose,
                'has_collateral' => $request->has_collateral ?? false,
                'collateral_type' => $request->collateral_type,
                'collateral_description' => $request->collateral_description,
                'collateral_value' => $request->collateral_value,
                'status' => 'pending',
                'created_by' => auth()->id()
            ]);

            // Add external guarantors
            $guarantorIds = [];
            foreach ($request->guarantors as $index => $guarantorData) {
                $guarantor = $loan->guarantors()->create([
                    'name' => $guarantorData['name'],
                    'phone' => $guarantorData['phone'],
                    'email' => $guarantorData['email'] ?? null,
                    'id_number' => $guarantorData['id_number'],
                    'occupation' => $guarantorData['occupation'] ?? null,
                    'address' => $guarantorData['address'] ?? null,
                    'relationship' => $guarantorData['relationship'] ?? null,
                    'status' => 'pending'
                ]);
                $guarantorIds[$index] = $guarantor->id;
            }

            // Handle document uploads (15MB limit per file)
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $index => $document) {
                    // Generate unique filename
                    $originalName = $document->getClientOriginalName();
                    $fileType = strtolower($document->getClientOriginalExtension());
                    $fileName = time() . '_' . $index . '_' . uniqid() . '.' . $fileType;

                    // Store file
                    $path = $document->storeAs('loan-documents/' . $loan->id, $fileName, 'public');

                    // Get category from form or determine from filename
                    $category = $request->document_categories[$index] ?? $this->determineDocumentCategory($originalName, $index);

                    // Link to guarantor if specified
                    $guarantorId = null;
                    if (isset($request->document_guarantor[$index]) && $request->document_guarantor[$index]) {
                        $guarantorIndex = str_replace('guarantor_', '', $request->document_guarantor[$index]);
                        if (isset($guarantorIds[$guarantorIndex])) {
                            $guarantorId = $guarantorIds[$guarantorIndex];
                        }
                    }

                    // Get description
                    $description = $request->document_descriptions[$index] ?? null;

                    // Create document record
                    LoanDocument::create([
                        'loan_application_id' => $loan->id,
                        'uploaded_by' => auth()->id(),
                        'document_name' => $originalName,
                        'original_name' => $originalName,
                        'file_path' => $path,
                        'file_type' => $fileType,
                        'file_size' => $document->getSize(),
                        'mime_type' => $document->getMimeType(),
                        'category' => $category,
                        'guarantor_id' => $guarantorId,
                        'description' => $description,
                        'verification_status' => 'pending'
                    ]);
                }
            }

            DB::commit();

            $documentCount = $request->hasFile('documents') ? count($request->file('documents')) : 0;
            return redirect()->route('admin.loans.index')
                ->with('success', 'Loan application created successfully with ' . count($request->guarantors) . ' guarantors and ' . $documentCount . ' documents.');

        } catch (\Exception $e) {
            DB::rollBack();

            // Delete uploaded files if any
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $document) {
                    // Clean up any partially uploaded files
                    if (isset($path)) {
                        Storage::disk('public')->delete($path);
                    }
                }
            }

            return redirect()->back()
                ->with('error', 'Error creating loan application: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Helper method to determine document category based on filename
     */
    private function determineDocumentCategory($filename, $index)
    {
        $filename = strtolower($filename);

        if (strpos($filename, 'guarantor') !== false || strpos($filename, 'id') !== false) {
            return 'guarantor_id';
        }
        if (strpos($filename, 'collateral') !== false) {
            if (strpos($filename, 'photo') !== false || in_array(pathinfo($filename, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png'])) {
                return 'collateral_photo';
            }
            return 'collateral_document';
        }
        if (strpos($filename, 'income') !== false || strpos($filename, 'salary') !== false || strpos($filename, 'payslip') !== false) {
            return 'proof_of_income';
        }
        if (strpos($filename, 'bank') !== false || strpos($filename, 'statement') !== false) {
            return 'bank_statement';
        }
        if (strpos($filename, 'application') !== false || strpos($filename, 'form') !== false) {
            return 'application_form';
        }

        return 'other';
    }

    /**
     * Assign treasurer to loan application for verification.
     */
    public function assignTreasurer(Request $request, LoanApplication $loan)
    {
        $validator = Validator::make($request->all(), [
            'treasurer_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $loan->update([
            'treasurer_id' => $request->treasurer_id,
            'status' => 'under_verification',
            'assigned_at' => now()
        ]);

        // Create notification for treasurer
        // You can implement notification system here

        return redirect()->route('admin.loans.show', $loan)
            ->with('success', 'Treasurer assigned successfully for verification.');
    }

    /**
     * Treasurer verifies the loan (checks collateral and guarantors).
     */
    public function verify(Request $request, LoanApplication $loan)
    {
        // Check if the logged-in user is the assigned treasurer
        if (auth()->id() != $loan->treasurer_id && !auth()->user()->hasRole('admin')) {
            return redirect()->back()
                ->with('error', 'You are not authorized to verify this loan.');
        }

        $validator = Validator::make($request->all(), [
            'verification_notes' => 'required|string|max:500',
            'guarantors_verified' => 'required|boolean',
            'collateral_verified' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update guarantor statuses if they were verified
        if ($request->guarantors_verified) {
            $loan->guarantors()->update(['status' => 'verified']);
        }

        DB::transaction(function () use ($loan, $request) {
            $loan->update([
                'status' => 'verified',
                'verification_notes' => $request->verification_notes,
                'verified_by' => auth()->id(),
                'verified_at' => now()
            ]);

            // Create activity log
            // You can implement activity logging here
        });

        return redirect()->route('admin.loans.show', $loan)
            ->with('success', 'Loan verified successfully. Ready for admin approval.');
    }

    /**
     * Admin approves the verified loan.
     */
    public function approve(Request $request, LoanApplication $loan)
    {
        // Only admin can approve
        if (!auth()->user()->hasRole('admin')) {
            return redirect()->back()
                ->with('error', 'Only administrators can approve loans.');
        }

        // Check if loan is verified
        if ($loan->status !== 'verified') {
            return redirect()->back()
                ->with('error', 'Loan must be verified before approval.');
        }

        $validator = Validator::make($request->all(), [
            'amount_approved' => 'required|numeric|min:1|max:' . $loan->amount_applied,
            'interest_rate' => 'required|numeric|min:0|max:100',
            'duration_months' => 'required|integer|min:1|max:60',
            'approval_notes' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Calculate monthly repayment and total repayable
        $principal = $request->amount_approved;
        $annualRate = $request->interest_rate / 100;
        $monthlyRate = $annualRate / 12;
        $months = $request->duration_months;

        // Simple interest calculation (you can use more complex formula if needed)
        $totalInterest = $principal * $annualRate * ($months / 12);
        $totalRepayable = $principal + $totalInterest;
        $monthlyRepayment = $totalRepayable / $months;

        DB::transaction(function () use ($loan, $request, $totalRepayable, $monthlyRepayment) {
            $loan->update([
                'status' => 'approved',
                'amount_approved' => $request->amount_approved,
                'interest_rate' => $request->interest_rate,
                'duration_months' => $request->duration_months,
                'monthly_repayment' => round($monthlyRepayment, 2),
                'total_repayable' => round($totalRepayable, 2),
                'approval_notes' => $request->approval_notes,
                'approved_by' => auth()->id(),
                'approved_at' => now()
            ]);

            // Create repayment schedule
            $this->createRepaymentSchedule($loan);
        });

        return redirect()->route('admin.loans.show', $loan)
            ->with('success', 'Loan approved successfully.');
    }

    /**
     * Reject loan application (can be done by treasurer or admin).
     */
    public function reject(Request $request, LoanApplication $loan)
    {
        // Check if user is authorized (admin or assigned treasurer)
        if (!auth()->user()->hasRole('admin') && auth()->id() != $loan->treasurer_id) {
            return redirect()->back()
                ->with('error', 'You are not authorized to reject this loan.');
        }

        $validator = Validator::make($request->all(), [
            'rejection_reason' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $loan->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'rejected_by' => auth()->id(),
            'rejected_at' => now()
        ]);

        return redirect()->route('admin.loans.index')
            ->with('success', 'Loan application rejected.');
    }

        public function downloadDocument($documentId)
{
    $document = LoanDocument::findOrFail($documentId);

    // Check authorization
    $loan = $document->loanApplication;
    if (!$loan) {
        return redirect()->back()->with('error', 'Document not associated with any loan.');
    }

    // Check if user is authorized (admin, treasurer assigned to this loan, or the loan owner)
    $user = auth()->user();
    $isAdmin = $user->hasRole('admin');
    $isAssignedTreasurer = ($loan->treasurer_id == $user->id);
    $isLoanOwner = ($loan->user_id == $user->id);

    if (!$isAdmin && !$isAssignedTreasurer && !$isLoanOwner) {
        return redirect()->back()->with('error', 'You are not authorized to download this document.');
    }

    // Check if file exists
    if (!Storage::disk('public')->exists($document->file_path)) {
        return redirect()->back()->with('error', 'Document file not found.');
    }

    // Get file from storage
    $filePath = Storage::disk('public')->path($document->file_path);

    // Return file download with proper headers
    return response()->download(
        $filePath,
        $document->original_name ?? $document->document_name,
        [
            'Content-Type' => $document->mime_type ?? 'application/octet-stream',
            'Content-Length' => $document->file_size
        ]
    );
}


    /**
 * Disburse approved loan.
 */
public function disburse(Request $request, LoanApplication $loan)
{
    if ($loan->status !== 'approved') {
        return redirect()->back()
            ->with('error', 'Only approved loans can be disbursed.');
    }

    DB::transaction(function () use ($loan, $request) {
        $loan->update([
            'status' => 'disbursed',
            'disbursed_by' => auth()->id(),
            'disbursed_at' => now()
        ]);

        // Create a transaction record
        PaymentTransaction::create([
            'transaction_number' => 'TXN-' . date('Y') . '-' . str_pad(PaymentTransaction::max('id') + 1, 6, '0', STR_PAD_LEFT),
            'user_id' => $loan->user_id,
            'amount' => $loan->amount_approved,
            'payment_type' => 'loan_disbursement',
            'payment_method' => $request->disbursement_method ?? 'cash',
            'payment_date' => now(),
            'receipt_number' => 'DR-' . date('Ymd') . '-' . str_pad($loan->id, 5, '0', STR_PAD_LEFT),
            'notes' => $request->disbursement_notes ?? 'Loan disbursement',
            'recorded_by' => auth()->id(),
            'status' => 'completed'
        ]);
    });

    return redirect()->route('admin.loans.show', $loan)
        ->with('success', 'Loan disbursed successfully.');
}

    /**
     * Mark loan as completed.
     */
    public function markCompleted(LoanApplication $loan)
    {
        if ($loan->status !== 'disbursed') {
            return redirect()->back()
                ->with('error', 'Only disbursed loans can be marked as completed.');
        }

        if ($loan->balance > 0) {
            return redirect()->back()
                ->with('error', 'Cannot mark loan as completed with outstanding balance.');
        }

        $loan->update(['status' => 'completed']);

        // Close defaulter tracking if exists
        if ($loan->defaulterTracking) {
            $loan->defaulterTracking->delete();
        }

        return redirect()->route('admin.loans.show', $loan)
            ->with('success', 'Loan marked as completed.');
    }

    /**
     * Follow up on defaulter.
     */
    public function followUpDefaulter(Request $request, DefaulterTracking $defaulter)
    {
        $validator = Validator::make($request->all(), [
            'follow_up_notes' => 'required|string|max:500',
            'next_follow_up' => 'nullable|date|after:today'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        // Update status based on current status
        $statusMap = [
            'new' => 'follow_up_1',
            'follow_up_1' => 'follow_up_2',
            'follow_up_2' => 'final_notice',
            'final_notice' => 'legal_action'
        ];

        $newStatus = $statusMap[$defaulter->status] ?? $defaulter->status;

        $defaulter->update([
            'status' => $newStatus,
            'follow_up_notes' => $request->follow_up_notes,
            'last_follow_up' => now(),
            'next_follow_up' => $request->next_follow_up
        ]);

        return redirect()->back()
            ->with('success', 'Follow up recorded successfully.');
    }

    /**
     * Assign defaulter to treasurer for follow up.
     */
    public function assignDefaulter(Request $request, DefaulterTracking $defaulter)
    {
        $validator = Validator::make($request->all(), [
            'assigned_to' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $defaulter->update([
            'assigned_to' => $request->assigned_to
        ]);

        return redirect()->back()
            ->with('success', 'Defaulter assigned for follow up.');
    }

    /**
     * Export loans report.
     */
    public function exportReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'nullable|string',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $query = LoanApplication::with(['user', 'treasurer', 'repayments']);

        if ($request->status && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $loans = $query->get();

        // Generate CSV or Excel report
        // You can implement export functionality here

        return redirect()->back()
            ->with('success', 'Report generated successfully.');
    }

    /**
     * Get loan statistics for dashboard.
     */
    public function getStatistics()
    {
        $stats = [
            'labels' => ['Pending', 'Under Verification', 'Verified', 'Approved', 'Disbursed', 'Completed', 'Defaulted'],
            'data' => [
                LoanApplication::pending()->count(),
                LoanApplication::underVerification()->count(),
                LoanApplication::verified()->count(),
                LoanApplication::approved()->count(),
                LoanApplication::disbursed()->count(),
                LoanApplication::completed()->count(),
                LoanApplication::defaulted()->count()
            ],
            'amounts' => [
                'pending' => LoanApplication::pending()->sum('amount_applied'),
                'approved' => LoanApplication::approved()->sum('amount_approved'),
                'disbursed' => LoanApplication::disbursed()->sum('amount_approved'),
                'repaid' => LoanRepayment::sum('amount_paid'),
                'outstanding' => LoanApplication::disbursed()->get()->sum('balance')
            ]
        ];

        return response()->json($stats);
    }

    /**
     * Bulk assign treasurer to multiple loans.
     */
    public function bulkAssignTreasurer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'loan_ids' => 'required|array|min:1',
            'loan_ids.*' => 'exists:loan_applications,id',
            'treasurer_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $updated = LoanApplication::whereIn('id', $request->loan_ids)
            ->where('status', 'pending')
            ->update([
                'treasurer_id' => $request->treasurer_id,
                'status' => 'under_verification',
                'assigned_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'message' => "{$updated} loans assigned to treasurer successfully."
        ]);
    }

    /**
     * Display applications (pending and under verification).
     */
    public function applications(Request $request)
    {
        $applications = LoanApplication::with('user')
            ->whereIn('status', ['pending', 'under_verification'])
            ->latest()
            ->paginate(15);

        return view('admin.loans.applications', compact('applications'));
    }

    /**
     * Create repayment schedule for approved loan.
     */
   /**
 * Create repayment schedule for approved loan.
 */
private function createRepaymentSchedule(LoanApplication $loan)
{
    $dueDate = now()->addMonth();
    $installmentNumber = 1;

    for ($i = 1; $i <= $loan->duration_months; $i++) {
        // Generate a unique repayment number
        $repaymentNumber = 'RPMT-' . $loan->id . '-' . date('Y') . '-' . str_pad($installmentNumber, 3, '0', STR_PAD_LEFT);

        $loan->repayments()->create([
            'repayment_number' => $repaymentNumber, // Add this line
            'installment_number' => $installmentNumber++,
            'due_date' => $dueDate->copy(),
            'amount_due' => $loan->monthly_repayment,
            'status' => 'pending'
        ]);

        $dueDate->addMonth();
    }
}
}
