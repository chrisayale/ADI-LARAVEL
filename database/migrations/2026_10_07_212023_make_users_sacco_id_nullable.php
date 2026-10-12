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
            // For SQLite, we need to recreate the table to modify column nullability
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
            DB::statement('INSERT INTO users_new (id, name, email, email_verified_at, password, remember_token, sacco_id, phone_number, address, national_id, membership_number, membership_date, status, next_of_kin_name, next_of_kin_phone, next_of_kin_relationship, profile_photo, created_at, updated_at, deleted_at)
                         SELECT id, name, email, email_verified_at, password, remember_token, sacco_id, phone_number, address, national_id, membership_number, membership_date, status, next_of_kin_name, next_of_kin_phone, next_of_kin_relationship, profile_photo, created_at, updated_at, deleted_at
                         FROM users');

            // Drop old table and rename new table
            Schema::dropIfExists('users');
            Schema::rename('users_new', 'users');
        } else {
            // For other databases (MySQL, PostgreSQL), use the standard approach
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('sacco_id')->nullable()->change();
                
                // Update foreign key constraint to use restrictOnDelete
                $table->foreign('sacco_id')
                    ->references('id')->on('sacos')
                    ->onDelete('restrict')
                    ->change();
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
            // For SQLite, we need to recreate the table to revert column nullability
            Schema::create('users_new', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->foreignId('sacco_id')->constrained('sacos')->onDelete('cascade');
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
            DB::statement('INSERT INTO users_new (id, name, email, email_verified_at, password, remember_token, sacco_id, phone_number, address, national_id, membership_number, membership_date, status, next_of_kin_name, next_of_kin_phone, next_of_kin_relationship, profile_photo, created_at, updated_at, deleted_at)
                         SELECT id, name, email, email_verified_at, password, remember_token, sacco_id, phone_number, address, national_id, membership_number, membership_date, status, next_of_kin_name, next_of_kin_phone, next_of_kin_relationship, profile_photo, created_at, updated_at, deleted_at
                         FROM users');

            // Drop old table and rename new table
            Schema::dropIfExists('users');
            Schema::rename('users_new', 'users');
        } else {
            // For other databases (MySQL, PostgreSQL), use the standard approach
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('sacco_id')->nullable(false)->change();
                
                // Revert foreign key constraint to use cascadeOnDelete for rollback
                $table->foreign('sacco_id')
                    ->references('id')->on('sacos')
                    ->onDelete('cascade')
                    ->change();
            });
        }
    }
};
