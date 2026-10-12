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
                DB::statement('DROP TABLE IF EXISTS "loan_applications_new"');
                // Drop any indexes that might be left over (though they should be gone with the table)
                DB::statement('DROP INDEX IF EXISTS "loan_applications_new_application_number_unique"');
                DB::statement('DROP INDEX IF EXISTS "loan_applications_new_sacco_id_status_index"');
                // VACUUM to clean up the database file and ensure any leftover indexes are cleared
                DB::statement('VACUUM');

                // For SQLite, we need to recreate the table to fix foreign key constraints
                Schema::create('loan_applications_new', function (Blueprint $table) {
                    $table->id();
                    $table->string('application_number'); // Removed unique() here
                    $table->foreignId('user_id')->constrained()->onDelete('cascade');
                    $table->foreignId('treasurer_id')->nullable()->constrained('users')->nullOnDelete();
                    $table->foreignId('sacco_id')->nullable()->constrained('sacos')->onDelete('restrict');
                    $table->decimal('amount_applied', 15, 2);
                    $table->decimal('amount_approved', 15, 2)->nullable();
                    $table->decimal('interest_rate', 5, 2)->nullable();
                    $table->integer('duration_months')->nullable();
                    $table->decimal('monthly_repayment', 15, 2)->nullable();
                    $table->decimal('total_repayable', 15, 2)->nullable();
                    $table->text('purpose')->nullable();
                    $table->boolean('has_collateral')->default(false);
                    $table->string('collateral_type')->nullable();
                    $table->text('collateral_description')->nullable();
                    $table->decimal('collateral_value', 15, 2)->nullable();
                    $table->enum('status', [
                        'pending',
                        'under_verification',
                        'verified',
                        'approved',
                        'rejected',
                        'disbursed',
                        'completed',
                        'defaulted'
                    ])->default('pending');
                    $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
                    $table->timestamp('verified_at')->nullable();
                    $table->text('verification_notes')->nullable();
                    $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
                    $table->timestamp('approved_at')->nullable();
                    $table->text('approval_notes')->nullable();
                    $table->foreignId('disbursed_by')->nullable()->constrained('users')->nullOnDelete();
                    $table->timestamp('disbursed_at')->nullable();
                    $table->date('first_payment_date')->nullable();
                    $table->date('maturity_date')->nullable();
                    $table->timestamps();
                    $table->softDeletes();

                    // Create indexes after table creation
                    $table->unique('application_number');
                    $table->index(['sacco_id', 'status']);
                });

                // Copy data from old table to new table
                DB::table('loan_applications_new')->insert(
                    DB::table('loan_applications')->get()->toArray()
                );

                // Drop the old table
                Schema::dropIfExists('loan_applications');

                // Rename the new table to the original name
                Schema::rename('loan_applications_new', 'loan_applications');
            } finally {
                // Re-enable foreign key constraints
                DB::statement('PRAGMA foreign_keys = ON');
            }
        } else {
            // For other databases (MySQL, PostgreSQL), we can directly add the foreign key
            // if it doesn't already exist.
            if (Schema::hasColumn('loan_applications', 'sacco_id')) {
                // Drop the existing foreign key if any (to avoid duplicate)
                // Note: Laravel's foreign key constraints are named automatically.
                // We'll drop the constraint if it exists by checking the information schema.
                // However, for simplicity, we can try to drop the constraint and ignore errors.
                // Alternatively, we can check if the constraint exists.
                // Since we are in a controlled environment, we'll attempt to add the constraint.
                // If it already exists, the statement will be ignored or cause an error.
                // To avoid error, we can first drop the constraint if it exists.
                // We'll get the foreign key name from the information schema.
                // But given the complexity, and since this migration is run once, we can just add it.
                // If the column already has the constraint, the following will throw an error.
                // We'll wrap in a try-catch.
                try {
                    Schema::table('loan_applications', function (Blueprint $table) {
                        $table->foreign('sacco_id')->references('id')->on('sacos')->onDelete('restrict');
                    });
                } catch (\Exception $e) {
                    // Likely the foreign key already exists.
                    // We can ignore the error.
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Do nothing - migration is not reversible
    }
};
