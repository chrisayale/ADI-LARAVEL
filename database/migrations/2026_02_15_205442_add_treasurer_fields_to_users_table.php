<?php
// database/migrations/2024_03_15_000001_add_treasurer_fields_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
        });

        // For PostgreSQL compatibility: change status column to text and add check constraint
        // instead of using enum which is not easily alterable in PostgreSQL
        DB::statement("ALTER TABLE users ALTER COLUMN status TYPE VARCHAR(255)");
        DB::statement("ALTER TABLE users ALTER COLUMN status SET DEFAULT 'pending'");
        DB::statement("ALTER TABLE users DROP CONSTRAINT IF EXISTS users_status_check");
        DB::statement("ALTER TABLE users ADD CONSTRAINT users_status_check CHECK (status IN ('active', 'inactive', 'suspended', 'pending'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['employee_id', 'branch', 'employment_date', 'position']);
        });

        // Revert status column
        DB::statement("ALTER TABLE users DROP CONSTRAINT IF EXISTS users_status_check");
        DB::statement("ALTER TABLE users ALTER COLUMN status SET DEFAULT 'active'");
    }
};
