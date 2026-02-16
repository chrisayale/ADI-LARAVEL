<?php
// database/migrations/[timestamp]_create_loan_guarantors_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_guarantors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_application_id')->constrained()->onDelete('cascade');

            // External guarantor details
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('id_number');
            $table->string('occupation')->nullable();
            $table->text('address')->nullable();
            $table->string('relationship')->nullable();

            // Verification details
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('verification_notes')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_guarantors');
    }
};
