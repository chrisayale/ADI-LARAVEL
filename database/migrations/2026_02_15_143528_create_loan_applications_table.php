<?php
// database/migrations/2024_01_01_000003_create_loan_applications_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Admin assigns a treasurer to handle verification/follow-up
            $table->foreignId('treasurer_id')->nullable()->constrained('users')->nullOnDelete();

            $table->decimal('amount_applied', 15, 2);
            $table->decimal('amount_approved', 15, 2)->nullable();

            // Make interest_rate nullable since it's set during approval
            $table->decimal('interest_rate', 5, 2)->nullable();

            $table->integer('duration_months')->nullable(); // Also make nullable
            $table->decimal('monthly_repayment', 15, 2)->nullable();
            $table->decimal('total_repayable', 15, 2)->nullable();
            $table->text('purpose')->nullable();

            // Collateral info
            $table->boolean('has_collateral')->default(false);
            $table->string('collateral_type')->nullable();
            $table->text('collateral_description')->nullable();
            $table->decimal('collateral_value', 15, 2)->nullable();

            // Application status
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

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_applications');
    }
};
