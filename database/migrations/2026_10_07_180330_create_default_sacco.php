<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Utiliser DB::table pour éviter la dépendance au modèle Eloquent pendant la migration
        $defaultSaccoId = DB::table('sacos')->insertGetId([
            'name' => env('DEFAULT_SACCO_NAME', 'Default SACCO for Migration'),
            'code' => 'DEFAULT',
            'registration_number' => 'MIGRATION-' . strtoupper(Str::random(10)),
            'status' => 'active',
            'primary_currency' => 'USD',
            'secondary_currency' => 'CDF',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Stocker l'ID pour les migrations suivantes dans une table temporaire
        Schema::create('sacco_migration_state', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->integer('value');
            $table->timestamps();
        });
        
        DB::table('sacco_migration_state')->insert([
            'key' => 'default_sacco_id',
            'value' => $defaultSaccoId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer la table de state
        Schema::dropIfExists('sacco_migration_state');
        
        // Note: Nous ne supprimons pas les données du SACCO par défaut ici
        // car cela serait fait dans les migrations de rollback suivantes
    }
};
