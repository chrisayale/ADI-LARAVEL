<?php

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
        // Check if we're using SQLite
        if (DB::getDriverName() === 'sqlite') {
            // Disable foreign key constraints for SQLite table recreation
            DB::statement('PRAGMA foreign_keys = OFF');

            try {
                // Drop any existing temporary table that might conflict
                Schema::dropIfExists('users_new');

                // For SQLite, we need to recreate the table to fix foreign key constraints
                Schema::create('users_new', function (Blueprint $table) {
                    $table->id();
                    $table->string('name');
                    $table->string('email')->unique();
                    $table->timestamp('email_verified_at')->nullable();
                    $table->string('password');
                    $table->rememberToken();
                    $table->foreignId('sacco_id')->nullable()->constrained('sacos')->onDelete('restrict');
                    $table->string('phone_number')->nullable();
                    $table->string('address')->nullable();
                    $table->string('national_id')->unique()->nullable();
                    $table->string('membership_number')->unique()->nullable();
                    $table->date('membership_date')->nullable();
                    $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
                    $table->string('next_of_kin_name')->nullable();
                    $table->string('next_of_kin_phone')->nullable();
                    $table->string('next_of_kin_relationship')->nullable();
                    $table->string('profile_photo')->nullable();
                    $table->timestamps();
                    $table->softDeletes();
                });

                // Copy data from old table to new table
                $sql = "INSERT INTO users_new (id, name, email, email_verified_at, password, remember_token, sacco_id, phone_number, address, national_id, membership_number, membership_date, status, next_of_kin_name, next_of_kin_phone, next_of_kin_relationship, profile_photo, created_at, updated_at, deleted_at)
                        SELECT id, name, email, email_verified_at, password, remember_token, sacco_id, phone_number, address, national_id, membership_number, membership_date, status, next_of_kin_name, next_of_kin_phone, next_of_kin_relationship, profile_photo, created_at, updated_at, deleted_at
                        FROM users";
                DB::statement($sql);

                // Drop old table and rename new table
                Schema::dropIfExists('users');
                Schema::rename('users_new', 'users');
            } finally {
                // Re-enable foreign key constraints
                DB::statement('PRAGMA foreign_keys = ON');
            }
        } else {
            // For other databases (MySQL, PostgreSQL), use the standard approach
            // First drop the incorrect foreign key constraint
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['sacco_id']); // This will drop ALL foreign keys on sacco_id
            });

            // Then add back the correct foreign key constraint
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('sacco_id')->nullable()->constrained('sacos')->onDelete('restrict');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if we're using SQLite
        if (DB::getDriverName() === 'sqlite') {
            // Disable foreign key constraints for SQLite table recreation
            DB::statement('PRAGMA foreign_keys = OFF');

            try {
                // Drop any existing temporary table that might conflict
                Schema::dropIfExists('users_new');

                // For SQLite, we need to recreate the table to restore the original state
                // Note: We don't know exactly what the original state was, but for rollback purposes
                // we'll recreate with both foreign keys (the correct one and the incorrect one)
                Schema::create('users_new', function (Blueprint $table) {
                    $table->id();
                    $table->string('name');
                    $table->string('email')->unique();
                    $table->timestamp('email_verified_at')->nullable();
                    $table->string('password');
                    $table->rememberToken();
                    $table->foreignId('sacco_id')->nullable();
                    $table->string('phone_number')->nullable();
                    $table->string('address')->nullable();
                    $table->string('national_id')->unique()->nullable();
                    $table->string('membership_number')->unique()->nullable();
                    $table->date('membership_date')->nullable();
                    $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
                    $table->string('next_of_kin_name')->nullable();
                    $table->string('next_of_kin_phone')->nullable();
                    $table->string('next_of_kin_relationship')->nullable();
                    $table->string('profile_photo')->nullable();
                    $table->timestamps();
                    $table->softDeletes();
                });

                // Copy data from old table to new table
                $sql = "INSERT INTO users_new (id, name, email, email_verified_at, password, remember_token, sacco_id, phone_number, address, national_id, membership_number, membership_date, status, next_of_kin_name, next_of_kin_phone, next_of_kin_relationship, profile_photo, created_at, updated_at, deleted_at)
                        SELECT id, name, email, email_verified_at, password, remember_token, sacco_id, phone_number, address, national_id, membership_number, membership_date, status, next_of_kin_name, next_of_kin_phone, next_of_kin_relationship, profile_photo, created_at, updated_at, deleted_at
                        FROM users";
                DB::statement($sql);

                // Drop old table and rename new table
                Schema::dropIfExists('users');
                Schema::rename('users_new', 'users');

                // Add back both foreign key constraints (for rollback to original broken state)
                Schema::table('users', function (Blueprint $table) {
                    $table->foreign('sacco_id')
                        ->references('id')->on('sacos')
                        ->onDelete('restrict');
                });

                Schema::table('users', function (Blueprint $table) {
                    $table->foreign('sacco_id')
                        ->references('id')->on('saccos') // This is the incorrect one
                        ->onDelete('restrict');
                });
            } finally {
                // Re-enable foreign key constraints
                DB::statement('PRAGMA foreign_keys = ON');
            }
        } else {
            // For other databases (MySQL, PostgreSQL), use the standard approach
            // First drop the foreign key constraint
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['sacco_id']);
            });

            // Then add back both foreign key constraints (for rollback to original broken state)
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('sacco_id')->nullable()->constrained('sacos')->onDelete('restrict');
            });

            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('sacco_id')->nullable()->constrained('saccos')->onDelete('restrict');
            });
        }
    }
};
