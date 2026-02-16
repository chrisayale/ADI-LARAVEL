<?php
// database/migrations/2024_01_01_000005_create_loan_repayments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_repayments', function (Blueprint $table) {
            $table->id();
            $table->string('repayment_number')->unique();
            $table->foreignId('loan_application_id')->constrained()->onDelete('cascade');
            $table->integer('installment_number');
            $table->decimal('amount_due', 15, 2);
            $table->decimal('amount_paid', 15, 2)->default(0);
            $table->decimal('penalty_amount', 15, 2)->default(0);
            $table->decimal('total_paid', 15, 2)->default(0);
            $table->date('due_date');
            $table->date('paid_date')->nullable();
            $table->string('receipt_number')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['pending', 'partial', 'paid', 'overdue'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['loan_application_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_repayments');
    }
};
