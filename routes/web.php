<?php
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\LoanController as AdminLoanController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\SavingsController;
use App\Http\Controllers\Admin\SaccoController as AdminSaccoController;
use App\Http\Controllers\Admin\RequestSaccoController as AdminRequestSaccoController;
use App\Http\Controllers\Treasurer\DashboardController as TreasurerDashboardController;
use App\Http\Controllers\Treasurer\SavingsController as TreasurerSavingsController;
use App\Http\Controllers\Treasurer\LoanVerificationController as TreasurerLoanVerificationController;
use App\Http\Controllers\Treasurer\RepaymentController as TreasurerRepaymentController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\Member\LoanApplicationController as MemberLoanApplicationController;
use App\Http\Controllers\Member\SavingsController as MemberSavingsController;
use App\Http\Controllers\Member\ProfileController as MemberProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdmin\SaccoController as SuperAdminSaccoController;
use App\Http\Controllers\RequestSaccoController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Public pages
Route::view('/services', 'services')->name('services');
Route::view('/testimonials', 'testimonials')->name('testimonials');
Route::view('/team', 'team')->name('team');

// Test route to verify routing is working
Route::get('/test-route', function () {
    return 'Test route working';
});


// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ==================== PROFILE ROUTES (Common for all authenticated users) ====================
Route::middleware('auth')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'show'])->name('show');
    Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
    Route::put('/', [ProfileController::class, 'update'])->name('update');
    Route::post('/photo', [ProfileController::class, 'updatePhoto'])->name('update-photo');
    Route::get('/recent-activity', [ProfileController::class, 'getRecentActivity'])->name('recent-activity');
});

// Dashboard redirect based on role
Route::middleware('auth')->get('/dashboard', function () {
    try {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('treasurer')) {
            return redirect()->route('treasurer.dashboard');
        } elseif ($user->hasRole('member')) {
            return redirect()->route('member.dashboard');
        }

        return view('dashboard');
    } catch (\Throwable $e) {
        dd([
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
    }
})->name('dashboard');

// ==================== NOTIFICATION ROUTES (COMPLETE) ====================
Route::middleware('auth')->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::get('/create', [NotificationController::class, 'create'])->name('create')->middleware('role:admin');
    Route::post('/send', [NotificationController::class, 'send'])->name('send')->middleware('role:admin');
    Route::get('/unread-count', [NotificationController::class, 'getUnreadCount'])->name('unread-count');
    Route::get('/recent', [NotificationController::class, 'getRecent'])->name('recent');
    Route::get('/{id}', [NotificationController::class, 'show'])->name('show');
    Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('mark-read');
    Route::post('/mark-all-read', [NotificationController::class, 'markAllRead'])->name('mark-all-read');
    Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
    Route::delete('/delete-all/read', [NotificationController::class, 'deleteAllRead'])->name('delete-all-read');

    // Admin only - get user notifications
    Route::get('/user/{userId}', [NotificationController::class, 'getUserNotifications'])->name('user-notifications')->middleware('role:admin');
});

// ==================== ADMIN ROUTES ====================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // User Management
    Route::resource('users', AdminUserController::class);
    Route::post('/users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');


    // ========== ADMIN PAYMENT MANAGEMENT ==========
    Route::prefix('payments')->name('payments.')->group(function () {
        // Payment collection
        Route::get('/collect', [App\Http\Controllers\Admin\PaymentController::class, 'collect'])->name('collect');
        Route::post('/process', [App\Http\Controllers\Admin\PaymentController::class, 'process'])->name('process');

        // AJAX endpoints
        Route::get('/loan-repayments', [App\Http\Controllers\Admin\PaymentController::class, 'getLoanRepayments'])->name('get-loan-repayments');
        Route::get('/member-summary/{member}', [App\Http\Controllers\Admin\PaymentController::class, 'getMemberSummary'])->name('member-summary');

        // Payment history and receipts
        Route::get('/history', [App\Http\Controllers\Admin\PaymentController::class, 'history'])->name('history');
        Route::get('/receipt/{transaction}', [App\Http\Controllers\Admin\PaymentController::class, 'printReceipt'])->name('receipt');
    });

    // ========== ADMIN LOAN MANAGEMENT ==========
    Route::prefix('loans')->name('loans.')->group(function () {
        // Dashboard and Statistics
        Route::get('/dashboard', [AdminLoanController::class, 'dashboard'])->name('dashboard');
        Route::get('/statistics', [AdminLoanController::class, 'getStatistics'])->name('statistics');

        // Main listing
        Route::get('/', [AdminLoanController::class, 'index'])->name('index');

        // Applications overview
        Route::get('/applications', [AdminLoanController::class, 'applications'])->name('applications');

        // Status-based views
        Route::get('/pending', [AdminLoanController::class, 'pending'])->name('pending');
        Route::get('/under-verification', [AdminLoanController::class, 'underVerification'])->name('under-verification');
        Route::get('/verified', [AdminLoanController::class, 'verified'])->name('verified');
        Route::get('/approved', [AdminLoanController::class, 'approved'])->name('approved');
        Route::get('/disbursed', [AdminLoanController::class, 'disbursed'])->name('disbursed');
        Route::get('/completed', [AdminLoanController::class, 'completed'])->name('completed');
        Route::get('/defaulters', [AdminLoanController::class, 'defaulters'])->name('defaulters');

        // CRUD operations
        Route::get('/create', [AdminLoanController::class, 'create'])->name('create');
        Route::post('/', [AdminLoanController::class, 'store'])->name('store');
        Route::get('/{loan}', [AdminLoanController::class, 'show'])->name('show');

        // Loan processing actions
        Route::post('/{loan}/assign-treasurer', [AdminLoanController::class, 'assignTreasurer'])->name('assign-treasurer');
        Route::post('/{loan}/verify', [AdminLoanController::class, 'verify'])->name('verify');
        Route::post('/{loan}/approve', [AdminLoanController::class, 'approve'])->name('approve');
        Route::post('/{loan}/reject', [AdminLoanController::class, 'reject'])->name('reject');
        Route::post('/{loan}/disburse', [AdminLoanController::class, 'disburse'])->name('disburse');
        Route::post('/{loan}/mark-completed', [AdminLoanController::class, 'markCompleted'])->name('mark-completed');


        // Document download route - ADD THIS
        Route::get('/documents/{document}/download', [AdminLoanController::class, 'downloadDocument'])->name('download-document');

        // Defaulter management
        Route::post('/defaulters/{defaulter}/follow-up', [AdminLoanController::class, 'followUpDefaulter'])->name('defaulters.follow-up');
        Route::post('/defaulters/{defaulter}/assign', [AdminLoanController::class, 'assignDefaulter'])->name('defaulters.assign');

        // Bulk operations
        Route::post('/bulk-assign-treasurer', [AdminLoanController::class, 'bulkAssignTreasurer'])->name('bulk-assign-treasurer');

        // Reports
        Route::get('/export-report', [AdminLoanController::class, 'exportReport'])->name('export-report');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/savings', [AdminReportController::class, 'savings'])->name('savings');
        Route::get('/loans', [AdminReportController::class, 'loans'])->name('loans');
        Route::get('/financial-summary', [AdminReportController::class, 'financialSummary'])->name('financial-summary');
        Route::get('/export', [AdminReportController::class, 'export'])->name('export');
    });


    // ========== ADMIN SAVINGS MANAGEMENT ==========
    Route::prefix('savings')->name('savings.')->group(function () {
        // ===== STATIC ROUTES (NO PARAMETERS) - These must come first =====

        // Main listing and create forms
        Route::get('/', [SavingsController::class, 'index'])->name('index');
        Route::get('/create', [SavingsController::class, 'create'])->name('create');
        Route::post('/', [SavingsController::class, 'store'])->name('store');

        // Type-based views
        Route::get('/deposits/list', [SavingsController::class, 'deposits'])->name('deposits');
        Route::get('/withdrawals/list', [SavingsController::class, 'withdrawals'])->name('withdrawals');

        // AJAX endpoints
        Route::get('/get-member-details', [SavingsController::class, 'getMemberDetails'])->name('get-member-details');
        Route::get('/member-summary', [SavingsController::class, 'memberSummary'])->name('member-summary');

        // ===== PARAMETER ROUTES ({saving}) - These must come LAST =====

        // Single transaction view
        Route::get('/{saving}', [SavingsController::class, 'show'])->name('show');

        // Transaction actions
        Route::post('/{saving}/approve', [SavingsController::class, 'approve'])->name('approve');
        Route::post('/{saving}/reject', [SavingsController::class, 'reject'])->name('reject');
        Route::get('/{saving}/print-receipt', [SavingsController::class, 'printReceipt'])->name('print-receipt');
    });

});

// ========== ADMIN SACCO MANAGEMENT (Super Admin only) ==========
Route::middleware(['auth', 'role:super_admin'])->prefix('admin/sacos')->name('admin.sacos.')->group(function () {
    Route::get('/', [AdminSaccoController::class, 'index'])->name('index');
    Route::get('/create', [AdminSaccoController::class, 'create'])->name('create');
    Route::post('/', [AdminSaccoController::class, 'store'])->name('store');
    Route::get('/{sacco}', [AdminSaccoController::class, 'show'])->name('show');
    Route::get('/{sacco}/edit', [AdminSaccoController::class, 'edit'])->name('edit');
    Route::put('/{sacco}', [AdminSaccoController::class, 'update'])->name('update');
    Route::delete('/{sacco}', [AdminSaccoController::class, 'destroy'])->name('destroy');
    Route::post('/{sacco}/toggle-official-account', [AdminSaccoController::class, 'toggleOfficialAccount'])->name('toggle-official-account');
    Route::post('/{sacco}/toggle-status', [AdminSaccoController::class, 'toggleStatus'])->name('toggle-status');
    Route::get('/{sacco}/official-account', [AdminSaccoController::class, 'editOfficialAccount'])->name('official-account');
    Route::post('/{sacco}/official-account', [AdminSaccoController::class, 'updateOfficialAccount'])->name('official-account.update');
});

// ==================== TREASURER ROUTES ====================
Route::middleware(['auth', 'role:treasurer'])->prefix('treasurer')->name('treasurer.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [TreasurerDashboardController::class, 'index'])->name('dashboard');

      // Loan Repayments - ADD THE MISSING COLLECT ROUTE HERE
    Route::prefix('repayments')->name('repayments.')->group(function () {
        // Add this route for collecting payments
        Route::get('/collect', [TreasurerRepaymentController::class, 'collect'])->name('collect');

        Route::get('/', [TreasurerRepaymentController::class, 'index'])->name('index');

        // AJAX endpoints
        Route::get('/get-loan-repayments', [TreasurerRepaymentController::class, 'getLoanRepayments'])->name('get-loan-repayments');
        Route::get('/member-summary/{member}', [TreasurerRepaymentController::class, 'getMemberSummary'])->name('member-summary');

        // Receipt
        Route::get('/receipt/{transaction}', [TreasurerRepaymentController::class, 'printReceipt'])->name('receipt');
        Route::get('/assigned', [TreasurerRepaymentController::class, 'assignedLoans'])->name('assigned');

          Route::post('/process', [TreasurerRepaymentController::class, 'process'])
            ->name('process');
               Route::get('/{loan}/repayments', [TreasurerRepaymentController::class, 'loanRepayments'])->name('loan-repayments');
    });

    // Alternative loans prefix routes
    Route::prefix('loans')->name('loans.')->group(function () {
        Route::get('/repayments', [TreasurerRepaymentController::class, 'index'])->name('repayments');
        Route::get('/assigned', [TreasurerRepaymentController::class, 'assignedLoans'])->name('assigned');
        Route::get('/{loan}/repayments', [TreasurerRepaymentController::class, 'loanRepayments'])->name('loan-repayments');
        Route::post('/repayments/record', [TreasurerRepaymentController::class, 'recordRepayment'])->name('record-repayment');
        Route::get('/receipt/{transaction}', [TreasurerRepaymentController::class, 'printReceipt'])->name('print-receipt');

        // Defaulter follow up
        Route::post('/{loan}/follow-up', [TreasurerRepaymentController::class, 'followUp'])->name('follow-up');
    });

    Route::prefix('savings')->name('savings.')->group(function () {
    // ===== STATIC ROUTES (NO PARAMETERS) - These must come first =====
    Route::get('/transactions', [TreasurerSavingsController::class, 'index'])->name('transactions');
    Route::get('/deposits', [TreasurerSavingsController::class, 'deposits'])->name('deposits');
    Route::get('/withdrawals', [TreasurerSavingsController::class, 'withdrawals'])->name('withdrawals');
    Route::get('/create', [TreasurerSavingsController::class, 'create'])->name('create');
    Route::post('/store', [TreasurerSavingsController::class, 'store'])->name('store');

    // ⚠️ IMPORTANT: Static AJAX routes must come BEFORE parameter routes
    Route::get('/get-member-details', [TreasurerSavingsController::class, 'getMemberDetails'])->name('get-member-details');

    // ===== PARAMETER ROUTES - These must come LAST =====
    Route::get('/{saving}', [TreasurerSavingsController::class, 'show'])->name('show');
    Route::get('/{saving}/print-receipt', [TreasurerSavingsController::class, 'printReceipt'])->name('print-receipt');
});

    // Loan Verification
    Route::prefix('verifications')->name('verifications.')->group(function () {
        Route::get('/pending', [TreasurerLoanVerificationController::class, 'pending'])->name('pending');
        Route::get('/verified', [TreasurerLoanVerificationController::class, 'verified'])->name('verified');
        Route::get('/{loan}', [TreasurerLoanVerificationController::class, 'show'])->name('show');
        Route::post('/{loan}/verify', [TreasurerLoanVerificationController::class, 'verify'])->name('verify');
        Route::post('/{loan}/reject', [TreasurerLoanVerificationController::class, 'reject'])->name('reject');

        // AJAX endpoints for individual verification
        Route::post('/guarantor/{guarantor}/verify', [TreasurerLoanVerificationController::class, 'verifyGuarantor'])->name('verify-guarantor');
        Route::post('/document/{document}/verify', [TreasurerLoanVerificationController::class, 'verifyDocument'])->name('verify-document');
    });


});

// ==================== SUPER ADMIN ROUTES ====================
Route::middleware(['auth', 'role:super_admin'])->prefix('super-admin')->name('super-admin.')->group(function () {
    // SACCO Management
    Route::prefix('sacos')->name('sacos.')->group(function () {
        Route::get('/', [SuperAdminSaccoController::class, 'index'])->name('index');
        Route::get('/create', [SuperAdminSaccoController::class, 'create'])->name('create');
        Route::post('/', [SuperAdminSaccoController::class, 'store'])->name('store');
        Route::get('/{sacco}', [SuperAdminSaccoController::class, 'show'])->name('show');
        Route::get('/{sacco}/edit', [SuperAdminSaccoController::class, 'edit'])->name('edit');
        Route::put('/{sacco}', [SuperAdminSaccoController::class, 'update'])->name('update');
        Route::delete('/{sacco}', [SuperAdminSaccoController::class, 'destroy'])->name('destroy');
        Route::post('/{sacco}/toggle-status', [SuperAdminSaccoController::class, 'toggleStatus'])->name('toggle-status');
        Route::get('/{sacco}/official-account', [SuperAdminSaccoController::class, 'editOfficialAccount'])->name('official-account');
        Route::post('/{sacco}/official-account', [SuperAdminSaccoController::class, 'updateOfficialAccount'])->name('official-account.update');

        // SACCO Context Management
        Route::post('/context/set', [SuperAdminSaccoController::class, 'setContext'])->name('context.set');
        Route::post('/context/clear', [SuperAdminSaccoController::class, 'clearContext'])->name('context.clear');
    });

    // SACCO Request Management
    Route::prefix('request-sacco')->name('request-sacco.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\RequestSaccoController::class, 'index'])->name('index');
        Route::get('/{requestSacco}', [App\Http\Controllers\Admin\RequestSaccoController::class, 'show'])->name('show');
        Route::get('/{requestSacco}/approve', [App\Http\Controllers\Admin\RequestSaccoController::class, 'approve'])->name('approve');
        Route::post('/{requestSacco}/approve', [App\Http\Controllers\Admin\RequestSaccoController::class, 'storeApproval'])->name('storeApproval');
        Route::get('/{requestSacco}/reject', [App\Http\Controllers\Admin\RequestSaccoController::class, 'reject'])->name('reject');
        Route::post('/{requestSacco}/reject', [App\Http\Controllers\Admin\RequestSaccoController::class, 'storeRejection'])->name('storeRejection');
    });
});

// ==================== MEMBER ROUTES ====================
Route::middleware(['auth', 'role:member'])->prefix('member')->name('member.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');

    // Savings
    Route::prefix('savings')->name('savings.')->group(function () {
        Route::get('/', [MemberSavingsController::class, 'index'])->name('my-savings');
        Route::get('/transactions', [MemberSavingsController::class, 'transactions'])->name('transactions');
    });

    // Member Loan Management
    Route::prefix('loans')->name('loans.')->group(function () {
        // Member loan applications
        Route::get('/apply', [MemberLoanApplicationController::class, 'create'])->name('apply');
        Route::post('/store', [MemberLoanApplicationController::class, 'store'])->name('store');
        Route::get('/applications', [MemberLoanApplicationController::class, 'index'])->name('applications');
        Route::get('/my-loans', [MemberLoanApplicationController::class, 'myLoans'])->name('my-loans');
        Route::get('/{loan}/schedule', [MemberLoanApplicationController::class, 'repaymentSchedule'])->name('repayment-schedule');
        Route::get('/{loan}', [MemberLoanApplicationController::class, 'show'])->name('show');
    });

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::post('/photo', [ProfileController::class, 'updatePhoto'])->name('update-photo');
    });
});
// Request SACCO routes (public for creating and storing)
Route::prefix('request-sacco')->name('request-sacco.')->group(function () {
    Route::get('/create', [RequestSaccoController::class, 'create'])->name('create');
    Route::post('/', [RequestSaccoController::class, 'store'])->name('store');
});

// Request SACCO routes (protected for authenticated users)
Route::middleware(['auth'])->prefix('request-sacco')->name('request-sacco.')->group(function () {
    Route::get('/', [RequestSaccoController::class, 'index'])->name('index');
});

// Admin routes for managing SACCO requests (Super Admin only)
Route::middleware(['auth', 'role:super_admin'])->prefix('admin/request-sacco')->name('admin.request-sacco.')->group(function () {
    Route::get('/', [AdminRequestSaccoController::class, 'index'])->name('index');
    Route::get('/{requestSacco}', [AdminRequestSaccoController::class, 'show'])->name('show');
    Route::get('/{requestSacco}/approve', [AdminRequestSaccoController::class, 'approve'])->name('approve');
    Route::post('/{requestSacco}/approve', [AdminRequestSaccoController::class, 'storeApproval'])->name('storeApproval');
    Route::get('/{requestSacco}/reject', [AdminRequestSaccoController::class, 'reject'])->name('reject');
    Route::post('/{requestSacco}/reject', [AdminRequestSaccoController::class, 'storeRejection'])->name('storeRejection');
});






Route::post('/test-csrf-debug', function (Request $request) {
    return response()->json([
        'session_token' => $request->session()->token(),
        'input_token' => $request->input('_token'),
        'header_token' => $request->header('X-CSRF-TOKEN'),
        'x_header_token' => $request->header('X-XSRF-TOKEN'),
    ]);
});
Route::get('/debug-requests', function () {
    $requests = App\Models\RequestSacco::with('requester')->get();
    foreach ($requests as $request) {
        echo "ID: {$request->id}, Name: {$request->name}, Code: {$request->code}, Status: {$request->status}<br>";
    }
});
Route::get('/debug-insert', function () {
    $requestSacco = App\Models\RequestSacco::create([
        'name' => 'Debug Test',
        'registration_number' => '0000',
        'address' => 'Debug Address',
        'phone_number' => '0000000000',
        'status' => 'pending',
    ]);
    return "Inserted ID: {$requestSacco->id}";
});

// Quick admin creation route (lightweight, no seeder)
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

Route::get('/quick-admin', function () {
    $user = User::updateOrCreate(
        ['email' => 'admin@sacco.com'],
        [
            'name' => 'Admin SACCO',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
            'is_active' => true,
            'email_verified_at' => now(),
        ]
    );

    // Assign Spatie role for hasRole() to work
    $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    $user->assignRole($adminRole);

    return 'Succès : Compte admin réactivé et prêt avec ID ' . $user->id;
});

