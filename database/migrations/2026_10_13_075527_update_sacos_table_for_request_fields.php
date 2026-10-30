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
            // Rename existing columns to match requested names
            $table->renameColumn('phone', 'mobile_money');
            $table->renameColumn('email', 'official_email');
            $table->renameColumn('city_village', 'city_quartier');
            $table->renameColumn('address', 'physical_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sacos', function (Blueprint $table) {
            // Rename back to original names
            $table->renameColumn('mobile_money', 'phone');
            $table->renameColumn('official_email', 'email');
            $table->renameColumn('city_quartier', 'city_village');
            $table->renameColumn('physical_address', 'address');
        });
    }
};