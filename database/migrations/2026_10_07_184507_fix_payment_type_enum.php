<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if we're using SQLite
        if (DB::getDriverName() === 'sqlite') {
            // SQLite-specific approach: recreate the table
            Schema::create('payment_transactions_new', function (Blueprint $table) {
                $table->id();
                $table->string('transaction_number')->unique();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('loan_repayment_id')->nullable()->constrained()->onDelete('set null');
                $table->decimal('amount', 15, 2);
                $table->enum('payment_type', ['savings_deposit', 'savings_withdrawal', 'loan_repayment', 'penalty', 'loan_disbursement']);
                $table->string('payment_method')->default('cash');
                $table->string('reference_number')->nullable();
                $table->date('payment_date');
                $table->string('receipt_number');
                $table->text('notes')->nullable();
                $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade');
                $table->enum('status', ['pending', 'completed', 'failed'])->default('completed');
                $table->foreignId('sacco_id')->constrained('sacos')->onDelete('restrict');
                $table->timestamps();
                $table->softDeletes();

                // Indexes
                $table->index(['user_id', 'created_at']);
                $table->index(['sacco_id', 'created_at']);
                $table->index('status');
            });

            // Copy data from old table to new table
            DB::statement('INSERT INTO payment_transactions_new (id, transaction_number, user_id, loan_repayment_id, amount, payment_type, payment_method, reference_number, payment_date, receipt_number, notes, recorded_by, status, created_at, updated_at, deleted_at, sacco_id)
                         SELECT id, transaction_number, user_id, loan_repayment_id, amount, payment_type, payment_method, reference_number, payment_date, receipt_number, notes, recorded_by, status, created_at, updated_at, deleted_at, sacco_id
                         FROM payment_transactions');

            // Drop old table and rename new table
            Schema::dropIfExists('payment_transactions');
            Schema::rename('payment_transactions_new', 'payment_transactions');
        } elseif (DB::getDriverName() === 'pgsql') {
            // For PostgreSQL: use VARCHAR + CHECK constraint instead of ENUM
            // First change column type to VARCHAR if it's an enum
            DB::statement("ALTER TABLE payment_transactions ALTER COLUMN payment_type TYPE VARCHAR(255)");
            // Drop existing check constraint if any
            DB::statement("ALTER TABLE payment_transactions DROP CONSTRAINT IF EXISTS payment_transactions_payment_type_check");
            // Add new check constraint with loan_disbursement
            DB::statement("ALTER TABLE payment_transactions ADD CONSTRAINT payment_transactions_payment_type_check CHECK (payment_type IN ('savings_deposit', 'savings_withdrawal', 'loan_repayment', 'penalty', 'loan_disbursement'))");
            // Ensure NOT NULL
            DB::statement("ALTER TABLE payment_transactions ALTER COLUMN payment_type SET NOT NULL");
        } else {
            // For MySQL, use the standard approach
            DB::statement("ALTER TABLE payment_transactions MODIFY payment_type ENUM('savings_deposit', 'savings_withdrawal', 'loan_repayment', 'penalty', 'loan_disbursement') NOT NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if we're using SQLite
        if (DB::getDriverName() === 'sqlite') {
            // SQLite-specific approach: recreate the table
            Schema::create('payment_transactions_new', function (Blueprint $table) {
                $table->id();
                $table->string('transaction_number')->unique();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('loan_repayment_id')->nullable()->constrained()->onDelete('set null');
                $table->decimal('amount', 15, 2);
                $table->enum('payment_type', ['savings_deposit', 'savings_withdrawal', 'loan_repayment', 'penalty']);
                $table->string('payment_method')->default('cash');
                $table->string('reference_number')->nullable();
                $table->date('payment_date');
                $table->string('receipt_number');
                $table->text('notes')->nullable();
                $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade');
                $table->enum('status', ['pending', 'completed', 'failed'])->default('completed');
                $table->foreignId('sacco_id')->constrained('sacos')->onDelete('restrict');
                $table->timestamps();
                $table->softDeletes();

                // Indexes
                $table->index(['user_id', 'created_at']);
                $table->index(['sacco_id', 'created_at']);
                $table->index('status');
            });

            // Copy data from old table to new table
            DB::statement('INSERT INTO payment_transactions_new (id, transaction_number, user_id, loan_repayment_id, amount, payment_type, payment_method, reference_number, payment_date, receipt_number, notes, recorded_by, status, created_at, updated_at, deleted_at, sacco_id)
                         SELECT id, transaction_number, user_id, loan_repayment_id, amount, payment_type, payment_method, reference_number, payment_date, receipt_number, notes, recorded_by, status, created_at, updated_at, deleted_at, sacco_id
                         FROM payment_transactions');

            // Drop old table and rename new table
            Schema::dropIfExists('payment_transactions');
            Schema::rename('payment_transactions_new', 'payment_transactions');
        } elseif (DB::getDriverName() === 'pgsql') {
            // For PostgreSQL: revert check constraint
            DB::statement("ALTER TABLE payment_transactions DROP CONSTRAINT IF EXISTS payment_transactions_payment_type_check");
            DB::statement("ALTER TABLE payment_transactions ADD CONSTRAINT payment_transactions_payment_type_check CHECK (payment_type IN ('savings_deposit', 'savings_withdrawal', 'loan_repayment', 'penalty'))");
        } else {
            // For MySQL, use the standard approach
            DB::statement("ALTER TABLE payment_transactions MODIFY payment_type ENUM('savings_deposit', 'savings_withdrawal', 'loan_repayment', 'penalty') NOT NULL");
        }
    }
};