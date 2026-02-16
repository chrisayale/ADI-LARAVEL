<?php
// database/migrations/2024_01_01_000007_create_defaulters_tracking_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('defaulters_tracking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_application_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount_overdue', 15, 2);
            $table->integer('days_overdue');
            $table->enum('status', ['new', 'follow_up_1', 'follow_up_2', 'final_notice', 'legal_action'])->default('new');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->text('follow_up_notes')->nullable();
            $table->date('last_follow_up')->nullable();
            $table->date('next_follow_up')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('defaulters_tracking');
    }
};
