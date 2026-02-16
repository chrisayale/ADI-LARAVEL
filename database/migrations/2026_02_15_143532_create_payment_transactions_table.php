<?php
// database/migrations/2024_01_01_000006_create_payment_transactions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
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
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
