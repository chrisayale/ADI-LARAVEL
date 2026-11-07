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
            $table->decimal('adjusted_daily_amount', 15, 2)->nullable()->after('late_penalty_amount');
            $table->decimal('theoretical_daily_amount', 15, 2)->nullable()->after('adjusted_daily_amount');
            $table->decimal('total_surplus_amount', 15, 2)->nullable()->after('theoretical_daily_amount');
            $table->enum('surplus_type', ['surplus', 'shortfall', 'none'])->default('none')->after('total_surplus_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            $table->dropColumn([
                'adjusted_daily_amount',
                'theoretical_daily_amount',
                'total_surplus_amount',
                'surplus_type'
            ]);
        });
    }
};