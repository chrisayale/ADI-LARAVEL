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
        Schema::create('zones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sacco_id')->constrained('sacos')->onDelete('cascade');
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['sacco_id', 'is_active']);
        });

        // Pivot table for collector-zone many-to-many relationship
        Schema::create('collector_zone', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collector_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('zone_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['collector_id', 'zone_id']);
            $table->index('collector_id');
            $table->index('zone_id');
        });

        // Add zone_id to users table for members (replacing collection_zone string)
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('zone_id')->nullable()->after('collection_zone')
                ->constrained('zones')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['zone_id']);
            $table->dropColumn('zone_id');
        });

        Schema::dropIfExists('collector_zone');
        Schema::dropIfExists('zones');
    }
};