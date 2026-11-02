<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            $table->enum('repayment_frequency', ['daily', 'weekly', 'monthly'])->default('monthly')->after('duration_months');
            $table->integer('duration_value')->nullable()->after('repayment_frequency')->comment('Duration in days/weeks/months based on frequency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            $table->dropColumn(['repayment_frequency', 'duration_value']);
        });
    }
};