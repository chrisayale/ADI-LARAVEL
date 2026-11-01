<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sacco;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create the KANZANZA FINANCE SACCO
        $sacco = Sacco::firstOrCreate(
            ['name' => 'KANZANZA FINANCE'],
            [
                'code' => 'KZF',
                'registration_number' => 'KZF-' . strtoupper(\Illuminate\Support\Str::random(10)),
                'status' => 'active',
                'primary_currency' => 'USD',
                'secondary_currency' => 'CDF',
            ]
        );

        // Create or update the admin user
        $user = User::updateOrCreate(
            ['email' => 'admin@kanzanza.com'],
            [
                'name' => 'Admin Kanzanza',
                'password' => Hash::make('kanzanza@2020'),
                'phone_number' => '+250700000000',
                'address' => 'Kigali, Rwanda',
                'national_id' => 'ADMIN-KANZANZA-001',
                'status' => 'active',
                'next_of_kin_name' => 'Emergency Contact',
                'next_of_kin_phone' => '+250700000001',
                'next_of_kin_relationship' => 'Emergency',
                'sacco_id' => $sacco->id,
                'membership_number' => 'ADM-' . date('Y') . '-' . str_pad(1, 5, '0', STR_PAD_LEFT),
                'membership_date' => now(),
            ]
        );

        // Assign admin role
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $user->assignRole('admin');
        }

        $this->command->info('Admin user created/updated successfully for KANZANZA FINANCE SACCO!');
        $this->command->info('Email: admin@kanzanza.com');
        $this->command->info('Password: kanzanza@2020');
        $this->command->info('SACCO: ' . $sacco->name);
    }
}