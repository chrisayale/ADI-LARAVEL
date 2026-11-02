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
        // SQLite doesn't support modifying enum directly, so we need to recreate the column
        Schema::table('loan_applications', function (Blueprint $table) {
            // For SQLite, we drop and re-add the column
            $table->dropColumn('amortization_method');
            $table->enum('amortization_method', ['pato_ndogo', 'french', 'flat_rate', 'constant_capital'])->default('pato_ndogo')->after('repayment_frequency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            $table->dropColumn('amortization_method');
            $table->enum('amortization_method', ['french', 'flat_rate', 'constant_capital'])->default('french')->after('repayment_frequency');
        });
    }
};
