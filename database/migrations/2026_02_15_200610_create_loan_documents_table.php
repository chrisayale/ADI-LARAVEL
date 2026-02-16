<?php
// database/migrations/2024_01_01_000008_create_loan_documents_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_documents', function (Blueprint $table) {
            $table->id();
            $table->string('document_number')->unique();
            $table->foreignId('loan_application_id')->constrained()->onDelete('cascade');
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');

            // Document details
            $table->string('document_name');
            $table->string('original_name');
            $table->string('file_path');
            $table->string('file_type'); // pdf, jpg, png, etc.
            $table->bigInteger('file_size'); // in bytes
            $table->string('mime_type')->nullable();
            $table->text('description')->nullable(); // ADD THIS LINE

            // Document category
            $table->enum('category', [
                'guarantor_id',
                'collateral_photo',
                'collateral_document',
                'proof_of_income',
                'bank_statement',
                'application_form',
                'other'
            ])->default('other');

            // For linking to specific guarantor if needed
            $table->foreignId('guarantor_id')->nullable()->constrained('loan_guarantors')->nullOnDelete();

            // Verification status
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('verification_notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['loan_application_id', 'category']);
            $table->index('verification_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_documents');
    }
};
