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
        // Convertir sacco_id en NOT NULL uniquement après vérification que toutes les données ont un sacco_id
        $tablesWithDirectSacco = [
            'users',
            'savings_transactions',
            'loan_applications',
            'loan_guarantors',
            'loan_repayments',
            'payment_transactions',
            'defaulters_tracking',
            'meeting_notices',
            'member_notifications'
        ];

        foreach ($tablesWithDirectSacco as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                // Pour SQLite, on doit recréer la table pour modifier une colonne en NOT NULL
                // Mais puisque nous venons de vérifier que toutes les lignes ont un sacco_id,
                // nous pouvons utiliser la méthode standard de Laravel
                $table->foreignId('sacco_id')->nullable(false)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Render sacco_id nullable again
        $tablesWithDirectSacco = [
            'users',
            'savings_transactions',
            'loan_applications',
            'loan_guarantors',
            'loan_repayments',
            'payment_transactions',
            'defaulters_tracking',
            'meeting_notices',
            'member_notifications'
        ];

        foreach ($tablesWithDirectSacco as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->foreignId('sacco_id')->nullable()->change();
            });
        }
    }
};
