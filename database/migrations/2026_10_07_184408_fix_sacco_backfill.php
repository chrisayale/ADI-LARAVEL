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
        // Check if the sacco_migration_state table exists
        if (Schema::hasTable('sacco_migration_state')) {
            // Get the default SACCO ID from the state table
            $defaultSaccoId = DB::table('sacco_migration_state')
                ->where('key', 'default_sacco_id')
                ->value('value');

            if (!$defaultSaccoId) {
                throw new \Exception('Default SACCO ID not found in migration state');
            }

            // Disable foreign key constraints temporarily to avoid SQLite issues
            DB::statement('PRAGMA foreign_keys = OFF');

            try {
                // Backfill all tables with sacco_id directly
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

                // Verification - make sure no records have NULL sacco_id
                foreach ($tablesWithDirectSacco as $tableName) {
                    $nullCount = DB::table($tableName)->whereNull('sacco_id')->count();
                    if ($nullCount > 0) {
                        throw new \Exception("$nullCount records in $tableName still have NULL sacco_id after backfill");
                    }
                }
            } finally {
                // Re-enable foreign key constraints
                DB::statement('PRAGMA foreign_keys = ON');
            }
        }

        // Clean up the temporary state table if it still exists
        Schema::dropIfExists('sacco_migration_state');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: Rolling back this migration would be complex as it would require
        // determining which data belonged to which SACCO originally.
        // In practice, we don't rollback this migration in production.

        // For development purposes, we'll just set sacco_id back to NULL
        // but this is NOT recommended for production

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
            DB::table($tableName)->update(['sacco_id' => null]);
        }
    }
};