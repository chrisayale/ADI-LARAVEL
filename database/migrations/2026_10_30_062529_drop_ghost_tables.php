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
        // Drop ghost tables created by SQLite FK constraint workarounds
        Schema::dropIfExists('payment_transactions_new');
        Schema::dropIfExists('users_new');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback needed - these are ghost tables
    }
};