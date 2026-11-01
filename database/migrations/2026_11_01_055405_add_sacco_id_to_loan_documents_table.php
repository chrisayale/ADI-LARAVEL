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
        Schema::table('loan_documents', function (Blueprint $table) {
            $table->foreignId('sacco_id')->nullable()->constrained('sacos')->onDelete('restrict');
            $table->index('sacco_id');
            $table->index(['sacco_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_documents', function (Blueprint $table) {
            $table->dropForeign(['sacco_id']);
            $table->dropIndex(['sacco_id']);
            $table->dropIndex(['sacco_id', 'created_at']);
            $table->dropColumn('sacco_id');
        });
    }
};
