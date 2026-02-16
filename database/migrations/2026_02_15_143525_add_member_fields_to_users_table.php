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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number')->nullable()->after('email');
            $table->string('address')->nullable()->after('phone_number');
            $table->string('national_id')->unique()->nullable()->after('address');
            $table->string('membership_number')->unique()->nullable()->after('national_id');
            $table->date('membership_date')->nullable()->after('membership_number');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->after('membership_date');
            $table->string('next_of_kin_name')->nullable()->after('status');
            $table->string('next_of_kin_phone')->nullable()->after('next_of_kin_name');
            $table->string('next_of_kin_relationship')->nullable()->after('next_of_kin_phone');
            $table->string('profile_photo')->nullable()->after('next_of_kin_relationship');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone_number',
                'address',
                'national_id',
                'membership_number',
                'membership_date',
                'status',
                'next_of_kin_name',
                'next_of_kin_phone',
                'next_of_kin_relationship',
                'profile_photo'
            ]);
        });
    }
};
