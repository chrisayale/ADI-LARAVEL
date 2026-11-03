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
        Schema::table('loan_repayments', function (Blueprint $table) {
            $table->decimal('adjusted_amount', 15, 2)->nullable()->after('amount_due');
            $table->decimal('difference_amount', 15, 2)->default(0)->after('adjusted_amount');
            $table->enum('difference_type', ['surplus', 'shortfall', 'none'])->default('none')->after('difference_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_repayments', function (Blueprint $table) {
            $table->dropColumn(['adjusted_amount', 'difference_amount', 'difference_type']);
        });
    }
};
