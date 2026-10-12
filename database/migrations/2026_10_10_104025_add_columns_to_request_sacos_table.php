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
        // Add columns only if they don't already exist
        if (!Schema::hasColumn('request_sacos', 'province')) {
            Schema::table('request_sacos', function (Blueprint $table) {
                $table->string('province')->nullable()->after('code');
            });
        }
        if (!Schema::hasColumn('request_sacos', 'territory')) {
            Schema::table('request_sacos', function (Blueprint $table) {
                $table->string('territory')->nullable()->after('province');
            });
        }
        if (!Schema::hasColumn('request_sacos', 'city_village')) {
            Schema::table('request_sacos', function (Blueprint $table) {
                $table->string('city_village')->nullable()->after('territory');
            });
        }
        if (!Schema::hasColumn('request_sacos', 'applicant_name')) {
            Schema::table('request_sacos', function (Blueprint $table) {
                $table->string('applicant_name')->nullable()->after('city_village');
            });
        }
        if (!Schema::hasColumn('request_sacos', 'applicant_role')) {
            Schema::table('request_sacos', function (Blueprint $table) {
                $table->string('applicant_role')->nullable()->after('applicant_name');
            });
        }
        if (!Schema::hasColumn('request_sacos', 'applicant_email')) {
            Schema::table('request_sacos', function (Blueprint $table) {
                $table->string('applicant_email')->nullable()->after('applicant_role');
            });
        }
        if (!Schema::hasColumn('request_sacos', 'applicant_phone')) {
            Schema::table('request_sacos', function (Blueprint $table) {
                $table->string('applicant_phone')->nullable()->after('applicant_email');
            });
        }
        if (!Schema::hasColumn('request_sacos', 'description')) {
            Schema::table('request_sacos', function (Blueprint $table) {
                $table->text('description')->nullable()->after('applicant_phone');
            });
        }
        if (!Schema::hasColumn('request_sacos', 'latitude')) {
            Schema::table('request_sacos', function (Blueprint $table) {
                $table->double('latitude', 10, 8)->nullable()->after('description');
            });
        }
        if (!Schema::hasColumn('request_sacos', 'longitude')) {
            Schema::table('request_sacos', function (Blueprint $table) {
                $table->double('longitude', 11, 8)->nullable()->after('latitude');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_sacos', function (Blueprint $table) {
            $table->dropColumn(['province', 'territory', 'city_village', 'applicant_name', 'applicant_role', 'applicant_email', 'applicant_phone', 'description', 'latitude', 'longitude']);
        });
    }
};
