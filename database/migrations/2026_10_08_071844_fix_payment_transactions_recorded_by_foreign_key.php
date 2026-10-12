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
            // Disable foreign key constraints for SQLite table recreation
            DB::statement('PRAGMA foreign_keys = OFF');

            try {
                // Drop any existing temporary table that might conflict
                DB::statement('DROP TABLE IF EXISTS payment_transactions_new');

                // For SQLite, we need to recreate the table to modify foreign key
                Schema::create('payment_transactions_new', function (Blueprint $table) {
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
                    $table->foreignId('recorded_by')->nullable()->constrained('users');
                    $table->enum('status', ['pending', 'completed', 'reversed'])->default('completed');
                    $table->timestamps();
                    $table->softDeletes();

                    // Indexes
                    $table->index(['user_id', 'created_at']);
                    $table->index(['sacco_id', 'created_at']);
                    $table->index('status');
                });

                // Copy data from old table to new table
                $sql = "INSERT INTO payment_transactions_new (id, transaction_number, user_id, loan_repayment_id, amount, payment_type, payment_method, reference_number, payment_date, receipt_number, notes, recorded_by, status, created_at, updated_at, deleted_at, sacco_id)
                        SELECT id, transaction_number, user_id, loan_repayment_id, amount, payment_type, payment_method, reference_number, payment_date, receipt_number, notes, recorded_by, status, created_at, updated_at, deleted_at, sacco_id
                        FROM payment_transactions";
                DB::statement($sql);

                // Drop old table and rename new table
                Schema::dropIfExists('payment_transactions');
                Schema::rename('payment_transactions_new', 'payment_transactions');

                // Now add the foreign key constraint with nullOnDelete
                Schema::table('payment_transactions', function (Blueprint $table) {
                    $table->foreign('recorded_by')
                        ->references('id')->on('users')
                        ->onDelete('set null');
                });
            } finally {
                // Re-enable foreign key constraints
                DB::statement('PRAGMA foreign_keys = ON');
            }
        } else {
            // For other databases (MySQL, PostgreSQL), use the standard approach
            Schema::table('payment_transactions', function (Blueprint $table) {
                // Drop existing foreign key constraint
                $table->dropForeign(['recorded_by']);

                // Recreate with nullable and nullOnDelete
                $table->foreignId('recorded_by')->nullable()->change();
                $table->foreign('recorded_by')
                    ->references('id')->on('users')
                    ->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if we're using SQLite
        if (DB::getDriverName() === 'sqlite') {
            // Disable foreign key constraints for SQLite table recreation
            DB::statement('PRAGMA foreign_keys = OFF');

            try {
                // Drop any existing temporary table that might conflict
                DB::statement('DROP TABLE IF EXISTS payment_transactions_new');

                // For SQLite, we need to recreate the table to revert foreign key
                Schema::create('payment_transactions_new', function (Blueprint $table) {
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
                    $table->foreignId('recorded_by')->constrained('users');
                    $table->enum('status', ['pending', 'completed', 'reversed'])->default('completed');
                    $table->timestamps();
                    $table->softDeletes();

                    // Indexes
                    $table->index(['user_id', 'created_at']);
                    $table->index(['sacco_id', 'created_at']);
                    $table->index('status');
                });

                // Copy data from old table to new table
                $sql = "INSERT INTO payment_transactions_new (id, transaction_number, user_id, loan_repayment_id, amount, payment_type, payment_method, reference_number, payment_date, receipt_number, notes, recorded_by, status, created_at, updated_at, deleted_at, sacco_id)
                        SELECT id, transaction_number, user_id, loan_repayment_id, amount, payment_type, payment_method, reference_number, payment_date, receipt_number, notes, recorded_by, status, created_at, updated_at, deleted_at, sacco_id
                        FROM payment_transactions";
                DB::statement($sql);

                // Drop old table and rename new table
                Schema::dropIfExists('payment_transactions');
                Schema::rename('payment_transactions_new', 'payment_transactions');

                // Now add the foreign key constraint back with cascade
                Schema::table('payment_transactions', function (Blueprint $table) {
                    $table->foreign('recorded_by')
                        ->references('id')->on('users')
                        ->onDelete('cascade');
                });
            } finally {
                // Re-enable foreign key constraints
                DB::statement('PRAGMA foreign_keys = ON');
            }
        } else {
            // For other databases (MySQL, PostgreSQL), use the standard approach
            Schema::table('payment_transactions', function (Blueprint $table) {
                // Drop existing foreign key constraint
                $table->dropForeign(['recorded_by']);

                // Recreate with cascade on delete for rollback
                $table->foreignId('recorded_by')->nullable(false)->change();
                $table->foreign('recorded_by')
                    ->references('id')->on('users')
                    ->onDelete('cascade');
            });
        }
    }
};
