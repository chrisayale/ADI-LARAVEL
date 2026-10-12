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
        // For SQLite, we need to disable foreign key constraints temporarily
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');
        }

        try {
            // Récupérer l'ID du SACCO par défaut depuis la table de state
            $defaultSaccoId = DB::table('sacco_migration_state')
                ->where('key', 'default_sacco_id')
                ->value('value');

            if (!$defaultSaccoId) {
                throw new \Exception('Default SACCO ID not found in migration state');
            }

            // Backfill toutes les tables avec sacco_id direct
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
                DB::table($tableName)->update(['sacco_id' => $defaultSaccoId]);
            }

            // Vérification d'intégrité - s'assurer qu'aucun enregistrement n'a sacco_id NULL
            foreach ($tablesWithDirectSacco as $tableName) {
                $nullCount = DB::table($tableName)->whereNull('sacco_id')->count();
                if ($nullCount > 0) {
                    throw new \Exception("$nullCount records in $tableName still have NULL sacco_id after backfill");
                }
            }

            // Nettoyer la table de state temporaire
            Schema::dropIfExists('sacco_migration_state');
        } finally {
            // Re-enable foreign key constraints for SQLite
            if (DB::getDriverName() === 'sqlite') {
                DB::statement('PRAGMA foreign_keys = ON');
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: Le rollback de cette migration serait complexe car il faudrait
        // déterminer quelles données appartenaient initialement à quel SACCO.
        // En pratique, on ne rollback pas cette migration en production.
        // Pour un environnement de développement, on pourrait remettre à NULL
        // mais cela n'est pas recommandé en production.

        // Pour les besoins de ce projet, nous laissons cette méthode vide
        // car le rollback complet serait géré par les migrations précédentes
        // qui supprimeraient les colonnes et la table saccos
    }
};