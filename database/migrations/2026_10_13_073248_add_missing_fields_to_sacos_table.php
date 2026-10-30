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
        Schema::table('sacos', function (Blueprint $table) {
            $table->string('province')->nullable()->after('code');
            $table->string('territory')->nullable()->after('province');
            $table->string('city_village')->nullable()->after('territory');
            $table->string('applicant_name')->nullable()->after('city_village');
            $table->string('applicant_role')->nullable()->after('applicant_name');
            $table->string('applicant_email')->nullable()->after('applicant_role');
            $table->string('applicant_phone')->nullable()->after('applicant_email');
            $table->text('description')->nullable()->after('applicant_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sacos', function (Blueprint $table) {
            $table->dropColumn(['province', 'territory', 'city_village', 'applicant_name', 'applicant_role', 'applicant_email', 'applicant_phone', 'description']);
        });
    }
};