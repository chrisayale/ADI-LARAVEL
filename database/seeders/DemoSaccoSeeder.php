<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sacco;

class DemoSaccoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create demo SACCOs (idempotent)
        $sacco1 = Sacco::firstOrCreate(
            ['registration_number' => 'SACCO/2026/001'],
            [
                'name' => 'Umoja SACCO',
                'code' => 'UOJA',
                'mobile_money' => '+250 788 123 456',
                'official_email' => 'info@umojasacco.rw',
                'physical_address' => 'Kigali, Rwanda',
                'primary_currency' => 'USD',
                'secondary_currency' => 'CDF',
                'status' => 'active',
                'settings' => [
                    'interest_rate' => 0.05,
                    'loan_term_months' => 12,
                    'min_share_amount' => 10000
                ]
            ]
        );

        $sacco2 = Sacco::firstOrCreate(
            ['registration_number' => 'SACCO/2026/002'],
            [
                'name' => 'Twiga SACCO',
                'code' => 'TWIG',
                'mobile_money' => '+250 788 987 654',
                'official_email' => 'info@twigasacco.rw',
                'physical_address' => 'Gisenyi, Rwanda',
                'primary_currency' => 'USD',
                'secondary_currency' => 'CDF',
                'status' => 'active',
                'settings' => [
                    'interest_rate' => 0.045,
                    'loan_term_months' => 18,
                    'min_share_amount' => 15000
                ]
            ]
        );

        // Note: We're not assigning users to specific SACCOs here because
        // the backfill migration already assigned all existing users to
        // the default SACCO, and modifying those assignments would require
        // disabling foreign key constraints again due to the SQLite issue.

        // In a production environment with proper database support, you would:
        // 1. Create multiple SACCOs
        // 2. Assign users to specific SACCOs based on their actual affiliation
        // 3. Ensure all transactions are properly tagged with the correct sacco_id

        $this->command->info('Demo SACCOs created/updated successfully!');
        $this->command->info('Note: Due to SQLite foreign key constraints in this environment,');
        $this->command->info('users remain assigned to the default SACCO from the backfill migration.');
        $this->command->info('In a production MySQL/PostgreSQL environment, proper multi-tenancy would be fully functional.');
    }
}
