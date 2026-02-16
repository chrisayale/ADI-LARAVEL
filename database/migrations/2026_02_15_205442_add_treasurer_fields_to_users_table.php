<?php
// database/migrations/2024_03_15_000001_add_treasurer_fields_to_users_table.php

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
        Schema::table('users', function (Blueprint $table) {
            // Treasurer specific fields
            $table->string('employee_id')->unique()->nullable()->after('membership_number');
            $table->string('branch')->nullable()->after('employee_id');
            $table->date('employment_date')->nullable()->after('branch');
            $table->string('position')->nullable()->after('employment_date');

            // Add 'pending' to status enum
            $table->enum('status', ['active', 'inactive', 'suspended', 'pending'])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['employee_id', 'branch', 'employment_date', 'position']);

            // Revert status enum
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->change();
        });
    }
};
