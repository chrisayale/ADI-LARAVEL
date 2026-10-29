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
                Schema::dropIfExists('savings_transactions_new');

                // For SQLite, we need to recreate the table to fix foreign key constraints
                Schema::create('savings_transactions_new', function (Blueprint $table) {
                    $table->id();
                    $table->string('transaction_number')->unique();
                    $table->foreignId('user_id')->constrained()->onDelete('cascade');
                    $table->foreignId('sacco_id')->nullable()->constrained('sacos')->onDelete('restrict');
                    $table->decimal('amount', 15, 2);
                    $table->enum('transaction_type', ['deposit', 'withdrawal']);
                    $table->date('transaction_date');
                    $table->string('receipt_number')->nullable();
                    $table->text('notes')->nullable();
                    $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade');
                    $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
                    $table->decimal('balance_after', 15, 2);
                    $table->timestamps();
                    $table->softDeletes();

                    $table->index(['sacco_id', 'created_at']);
                });

                // Copy data from old table to new table
                $sql = "INSERT INTO savings_transactions_new (id, transaction_number, user_id, sacco_id, amount, transaction_type, transaction_date, receipt_number, notes, recorded_by, status, balance_after, created_at, updated_at, deleted_at)
                        SELECT id, transaction_number, user_id, sacco_id, amount, transaction_type, transaction_date, receipt_number, notes, recorded_by, status, balance_after, created_at, updated_at, deleted_at
                        FROM savings_transactions";
                DB::statement($sql);

                // Drop old table and rename new table
                Schema::dropIfExists('savings_transactions');
                Schema::rename('savings_transactions_new', 'savings_transactions');
            } finally {
                // Re-enable foreign key constraints
                DB::statement('PRAGMA foreign_keys = ON');
            }
        } else {
            // For other databases (MySQL, PostgreSQL), only fix foreign key constraint
            // Drop incorrect foreign key if it exists (on 'saccos' table)
            $foreignKeys = DB::select("
                SELECT conname
                FROM pg_constraint
                WHERE conrelid = 'savings_transactions'::regclass
                AND contype = 'f'
                AND conname LIKE '%sacco_id%'
            ");

            foreach ($foreignKeys as $fk) {
                $fkDetails = DB::select("
                    SELECT confrelid::regclass as referenced_table
                    FROM pg_constraint
                    WHERE conname = ?
                ", [$fk->conname]);

                if (!empty($fkDetails) && $fkDetails[0]->referenced_table === 'saccos') {
                    // This is the incorrect foreign key referencing 'saccos' instead of 'sacos'
                    DB::statement("ALTER TABLE savings_transactions DROP CONSTRAINT {$fk->conname}");
                }
            }

            // Ensure the correct foreign key exists (on 'sacos' table)
            $correctFkExists = DB::select("
                SELECT 1
                FROM pg_constraint
                WHERE conrelid = 'savings_transactions'::regclass
                AND contype = 'f'
                AND confrelid = 'sacos'::regclass
                AND conname LIKE '%sacco_id%'
            ");

            if (empty($correctFkExists)) {
                DB::statement("ALTER TABLE savings_transactions ADD CONSTRAINT savings_transactions_sacco_id_foreign FOREIGN KEY (sacco_id) REFERENCES sacos(id) ON DELETE RESTRICT");
            }
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
                Schema::dropIfExists('savings_transactions_new');

                // For SQLite, we need to recreate the table to restore original state
                // Note: We don't know exactly what the original state was, but for rollback purposes
                // we'll recreate with the incorrect foreign key (to match pre-fix state)
                Schema::create('savings_transactions_new', function (Blueprint $table) {
                    $table->id();
                    $table->string('transaction_number')->unique();
                    $table->foreignId('user_id')->constrained()->onDelete('cascade');
                    $table->foreignId('sacco_id')->nullable();
                    $table->decimal('amount', 15, 2);
                    $table->enum('transaction_type', ['deposit', 'withdrawal']);
                    $table->date('transaction_date');
                    $table->string('receipt_number')->nullable();
                    $table->text('notes')->nullable();
                    $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade');
                    $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
                    $table->decimal('balance_after', 15, 2);
                    $table->timestamps();
                    $table->softDeletes();

                    $table->index(['sacco_id', 'created_at']);
                });

                // Copy data from old table to new table
                $sql = "INSERT INTO savings_transactions_new (id, transaction_number, user_id, sacco_id, amount, transaction_type, transaction_date, receipt_number, notes, recorded_by, status, balance_after, created_at, updated_at, deleted_at)
                        SELECT id, transaction_number, user_id, sacco_id, amount, transaction_type, transaction_date, receipt_number, notes, recorded_by, status, balance_after, created_at, updated_at, deleted_at
                        FROM savings_transactions";
                DB::statement($sql);

                // Drop old table and rename new table
                Schema::dropIfExists('savings_transactions');
                Schema::rename('savings_transactions_new', 'savings_transactions');

                // Add back the incorrect foreign key constraint (for rollback)
                Schema::table('savings_transactions', function (Blueprint $table) {
                    $table->foreign('sacco_id')
                        ->references('id')->on('saccos')
                        ->onDelete('restrict');
                });
            } finally {
                // Re-enable foreign key constraints
                DB::statement('PRAGMA foreign_keys = ON');
            }
        } else {
            // For other databases (MySQL, PostgreSQL), restore incorrect foreign key for rollback
            // Drop current foreign key
            $foreignKeys = DB::select("
                SELECT conname
                FROM pg_constraint
                WHERE conrelid = 'savings_transactions'::regclass
                AND contype = 'f'
                AND conname LIKE '%sacco_id%'
            ");

            foreach ($foreignKeys as $fk) {
                DB::statement("ALTER TABLE savings_transactions DROP CONSTRAINT {$fk->conname}");
            }

            // Add back the incorrect foreign key constraint (for rollback)
            DB::statement("ALTER TABLE savings_transactions ADD CONSTRAINT savings_transactions_sacco_id_foreign FOREIGN KEY (sacco_id) REFERENCES saccos(id) ON DELETE RESTRICT");
        }
    }
};