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
        // Ajouter sacco_id NULLABLE sur les tables nécessitant un sacco_id direct
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
            Schema::table($tableName, function (Blueprint $blueprint) use ($tableName) {
                $blueprint->foreignId('sacco_id')->nullable()->constrained('sacos')->onDelete('restrict');
                $blueprint->index('sacco_id');

                // Indexes supplémentaires pour les tables financières critiques
                if (in_array($tableName, ['savings_transactions', 'loan_applications', 'loan_repayments', 'payment_transactions'])) {
                    $blueprint->index(['sacco_id', 'created_at']);
                }

                if ($tableName === 'users') {
                    $blueprint->index(['sacco_id', 'status']);
                }

                if (in_array($tableName, ['loan_applications', 'loan_repayments'])) {
                    $blueprint->index(['sacco_id', 'status']);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer les colonnes sacco_id des tables
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
            Schema::table($tableName, function (Blueprint $blueprint) {
                $blueprint->dropForeign(['sacco_id']);
                $blueprint->dropIndex(['sacco_id']);
                $blueprint->dropColumn('sacco_id');

                // Supprimer aussi les indexes composites
                if (in_array($tableName, ['savings_transactions', 'loan_applications', 'loan_repayments', 'payment_transactions'])) {
                    $blueprint->dropIndex(['sacco_id', 'created_at']);
                }

                if ($tableName === 'users') {
                    $blueprint->dropIndex(['sacco_id', 'status']);
                }

                if (in_array($tableName, ['loan_applications', 'loan_repayments'])) {
                    $blueprint->dropIndex(['sacco_id', 'status']);
                }
            });
        }
    }
};
