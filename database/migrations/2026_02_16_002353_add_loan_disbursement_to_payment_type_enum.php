// php artisan make:migration add_loan_disbursement_to_payment_type_enum
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Check if we're using SQLite
        if (DB::getDriverName() === 'sqlite') {
            // For SQLite, we need to recreate the table with the correct structure
            // First check if sacco_id column exists in payment_transactions table
            $hasSaccoId = Schema::hasColumn('payment_transactions', 'sacco_id');

            // Drop any existing temporary tables that might conflict
            $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name LIKE 'payment_transactions_new%'");
            foreach ($tables as $table) {
                DB::statement('DROP TABLE IF EXISTS ' . $table->name);
            }

            // Use a timestamp in the temporary table name to avoid conflicts
            $tempTableName = 'payment_transactions_new_' . time();

            Schema::create($tempTableName, function (Blueprint $table) use ($hasSaccoId) {
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
                $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade');
                $table->enum('status', ['pending', 'completed', 'reversed'])->default('completed');
                $table->timestamps();

                // Only add sacco_id column if we're tracking it (should be true after the sacco_id migrations)
                if ($hasSaccoId) {
                    $table->foreignId('sacco_id')->nullable()->constrained()->onDelete('restrict');
                }

                $table->softDeletes();

                // Indexes
                $table->index(['user_id', 'status']);
                $table->index(['sacco_id', 'status']);
            });

            // Copy data from old table to new table
            // We need to map the old columns to the new table structure
            // Note: In the current table, we have 'notes' column, and 'status' and 'payment_type' as enum
            if ($hasSaccoId) {
                DB::statement("INSERT INTO {$tempTableName} (id, transaction_number, user_id, loan_repayment_id, amount, payment_type, payment_method, reference_number, payment_date, receipt_number, notes, recorded_by, status, created_at, updated_at, sacco_id, deleted_at)
                             SELECT id,
                                    NULL,  -- transaction_number
                                    user_id,
                                    loan_repayment_id,
                                    amount,
                                    payment_type,
                                    'cash',  -- payment_method
                                    reference_number,
                                    payment_date,
                                    NULL,  -- receipt_number
                                    notes,  -- notes
                                    recorded_by,
                                    status,
                                    created_at,
                                    updated_at,
                                    sacco_id,
                                    NULL   -- deleted_at
                             FROM payment_transactions");
            } else {
                DB::statement("INSERT INTO {$tempTableName} (id, transaction_number, user_id, loan_repayment_id, amount, payment_type, payment_method, reference_number, payment_date, receipt_number, notes, recorded_by, status, created_at, updated_at, deleted_at)
                             SELECT id,
                                    NULL,  -- transaction_number
                                    user_id,
                                    loan_repayment_id,
                                    amount,
                                    payment_type,
                                    'cash',  -- payment_method
                                    reference_number,
                                    payment_date,
                                    NULL,  -- receipt_number
                                    notes,  -- notes
                                    recorded_by,
                                    status,
                                    created_at,
                                    updated_at,
                                    NULL   -- deleted_at
                             FROM payment_transactions");
            }

            // Drop old table and rename new table
            Schema::dropIfExists('payment_transactions');
            Schema::rename($tempTableName, 'payment_transactions');
        } elseif (DB::getDriverName() === 'pgsql') {
            // For PostgreSQL: use VARCHAR + CHECK constraint instead of ENUM
            // First change column type to VARCHAR if it's an enum
            DB::statement("ALTER TABLE payment_transactions ALTER COLUMN payment_type TYPE VARCHAR(255)");
            // Drop existing check constraint if any
            DB::statement("ALTER TABLE payment_transactions DROP CONSTRAINT IF EXISTS payment_transactions_payment_type_check");
            // Add new check constraint with loan_disbursement
            DB::statement("ALTER TABLE payment_transactions ADD CONSTRAINT payment_transactions_payment_type_check CHECK (payment_type IN ('savings_deposit', 'savings_withdrawal', 'loan_repayment', 'penalty', 'loan_disbursement'))");
        } else {
            // For MySQL, use the direct approach
            DB::statement("ALTER TABLE payment_transactions MODIFY payment_type ENUM('savings_deposit', 'savings_withdrawal', 'loan_repayment', 'penalty', 'loan_disbursement') NOT NULL");
        }
    }

    public function down(): void
    {
        // Check if we're using SQLite
        if (DB::getDriverName() === 'sqlite') {
            // For SQLite, we need to recreate the table
            // First check if sacco_id column exists in payment_transactions table
            $hasSaccoId = Schema::hasColumn('payment_transactions', 'sacco_id');

            // Drop any existing temporary tables that might conflict
            $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name LIKE 'payment_transactions_new%'");
            foreach ($tables as $table) {
                DB::statement('DROP TABLE IF EXISTS ' . $table->name);
            }

            // Use a timestamp in the temporary table name to avoid conflicts
            $tempTableName = 'payment_transactions_new_' . time();

            Schema::create($tempTableName, function (Blueprint $table) use ($hasSaccoId) {
                $table->id();
                $table->string('transaction_number')->unique();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('loan_repayment_id')->nullable()->constrained()->nullOnDelete();
                $table->decimal('amount', 15, 2);
                $table->enum('payment_type', ['savings_deposit', 'savings_withdrawal', 'loan_repayment', 'penalty']);
                $table->enum('payment_method', ['cash', 'bank_transfer', 'mobile_money'])->default('cash');
                $table->string('reference_number')->nullable();
                $table->date('payment_date');
                $table->string('receipt_number')->unique();
                $table->text('notes')->nullable();
                $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade');
                $table->enum('status', ['pending', 'completed', 'reversed'])->default('completed');
                $table->timestamps();

                // Only add sacco_id column if we're tracking it
                if ($hasSaccoId) {
                    $table->foreignId('sacco_id')->nullable()->constrained()->onDelete('restrict');
                }

                $table->softDeletes();

                // Indexes
                $table->index(['user_id', 'status']);
                $table->index(['sacco_id', 'status']);
            });

            // Copy data from old table to new table
            // We need to map the old columns to the new table structure
            if ($hasSaccoId) {
                DB::statement("INSERT INTO {$tempTableName} (id, transaction_number, user_id, loan_repayment_id, amount, payment_type, payment_method, reference_number, payment_date, receipt_number, notes, recorded_by, status, created_at, updated_at, sacco_id, deleted_at)
                             SELECT id,
                                    NULL,  -- transaction_number
                                    user_id,
                                    loan_repayment_id,
                                    amount,
                                    payment_type,
                                    'cash',  -- payment_method
                                    reference_number,
                                    payment_date,
                                    NULL,  -- receipt_number
                                    notes,  -- notes
                                    recorded_by,
                                    status,
                                    created_at,
                                    updated_at,
                                    sacco_id,
                                    NULL   -- deleted_at
                             FROM payment_transactions");
            } else {
                DB::statement("INSERT INTO {$tempTableName} (id, transaction_number, user_id, loan_repayment_id, amount, payment_type, payment_method, reference_number, payment_date, receipt_number, notes, recorded_by, status, created_at, updated_at, deleted_at)
                             SELECT id,
                                    NULL,  -- transaction_number
                                    user_id,
                                    loan_repayment_id,
                                    amount,
                                    payment_type,
                                    'cash',  -- payment_method
                                    reference_number,
                                    payment_date,
                                    NULL,  -- receipt_number
                                    notes,  -- notes
                                    recorded_by,
                                    status,
                                    created_at,
                                    updated_at,
                                    NULL   -- deleted_at
                             FROM payment_transactions");
            }

            // Drop old table and rename new table
            Schema::dropIfExists('payment_transactions');
            Schema::rename($tempTableName, 'payment_transactions');
        } elseif (DB::getDriverName() === 'pgsql') {
            // For PostgreSQL: revert check constraint
            DB::statement("ALTER TABLE payment_transactions DROP CONSTRAINT IF EXISTS payment_transactions_payment_type_check");
            DB::statement("ALTER TABLE payment_transactions ADD CONSTRAINT payment_transactions_payment_type_check CHECK (payment_type IN ('savings_deposit', 'savings_withdrawal', 'loan_repayment', 'penalty'))");
        } else {
            // For MySQL, use the direct approach
            DB::statement("ALTER TABLE payment_transactions MODIFY payment_type ENUM('savings_deposit', 'savings_withdrawal', 'loan_repayment', 'penalty') NOT NULL");
        }
    }
};