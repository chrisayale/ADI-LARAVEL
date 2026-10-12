<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Disable foreign keys
DB::statement('PRAGMA foreign_keys = OFF');

// Create new table
Schema::create('payment_transactions_new', function ($table) {
    $table->id();
    $table->string('transaction_number')->unique();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('loan_repayment_id')->nullable()->constrained()->nullOnDelete();
    $table->decimal('amount', 15, 2);
    $table->enum('payment_type', ['savings_deposit', 'savings_withdrawal', 'loan_repayment', 'penalty', 'loan_disbursement']);
    $table->enum('payment_method', ['cash', 'bank_transfer', 'mobile_money'])->default('cash');
    $table->string('reference_number')->nullable();
    $table->date('payment_date');
    $table->string('receipt_number')->unique();
    $table->text('notes')->nullable();
    $table->foreignId('recorded_by')->nullable()->constrained('users')->onDelete('set null');
    $table->enum('status', ['pending', 'completed', 'reversed'])->default('completed');
    $table->timestamps();
    $table->softDeletes();

    // Indexes
    $table->index(['user_id', 'created_at']);
    $table->index(['sacco_id', 'created_at']);
    $table->index('status');
});

// Copy data
DB::statement('INSERT INTO payment_transactions_new (id, transaction_number, user_id, loan_repayment_id, amount, payment_type, payment_method, reference_number, payment_date, receipt_number, notes, recorded_by, status, created_at, updated_at, deleted_at, sacco_id)
             SELECT id, transaction_number, user_id, loan_repayment_id, amount, payment_type, payment_method, reference_number, payment_date, receipt_number, notes, recorded_by, status, created_at, updated_at, deleted_at, sacco_id
             FROM payment_transactions');

// Drop old table
Schema::dropIfExists('payment_transactions');

// Rename new table
Schema::rename('payment_transactions_new', 'payment_transactions');

// Re-enable foreign keys
DB::statement('PRAGMA foreign_keys = ON');

echo "Migration completed successfully.\n";
