<?php
// database/seeders/RolePermissionSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions (idempotent)
        $permissions = [
            'access admin dashboard',
            'access treasurer dashboard',
            'access member dashboard',
            'view members',
            'create members',
            'edit members',
            'view savings',
            'record deposits',
            'record withdrawals',
            'view loans',
            'apply for loans',
            'verify loans',
            'approve loans',
            'disburse loans',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web'],
                ['name' => $permission, 'guard_name' => 'web']
            );
        }

        // Create roles (idempotent)
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => 'web'],
            ['name' => 'admin', 'guard_name' => 'web']
        );
        $adminRole->givePermissionTo(Permission::all());

        $treasurerRole = Role::firstOrCreate(
            ['name' => 'treasurer', 'guard_name' => 'web'],
            ['name' => 'treasurer', 'guard_name' => 'web']
        );
        $treasurerRole->givePermissionTo([
            'access treasurer dashboard',
            'view members',
            'view savings',
            'record deposits',
            'record withdrawals',
            'view loans',
            'verify loans',
        ]);

        $memberRole = Role::firstOrCreate(
            ['name' => 'member', 'guard_name' => 'web'],
            ['name' => 'member', 'guard_name' => 'web']
        );
        $memberRole->givePermissionTo([
            'access member dashboard',
            'view savings',
            'apply for loans',
            'view loans',
        ]);

        // Create users (idempotent)
        $admin = User::firstOrCreate(
            ['email' => 'admin@sacco.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
                'phone_number' => '+256700000001',
                'address' => 'Kampala',
                'national_id' => 'ADMIN001',
                'membership_number' => 'ADMIN-2024-001',
                'membership_date' => now(),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('admin');

        $treasurer = User::firstOrCreate(
            ['email' => 'treasurer@sacco.com'],
            [
                'name' => 'John Treasurer',
                'password' => Hash::make('password'),
                'phone_number' => '+256700000002',
                'address' => 'Kampala',
                'national_id' => 'TREAS001',
                'membership_number' => 'TREAS-2024-001',
                'membership_date' => now(),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $treasurer->assignRole('treasurer');

        $member = User::firstOrCreate(
            ['email' => 'alice@ssacco.com'],
            [
                'name' => 'Alice Member',
                'password' => Hash::make('password'),
                'phone_number' => '+256700000003',
                'address' => 'Kampala',
                'national_id' => 'MEM001',
                'membership_number' => 'MEM-2024-001',
                'membership_date' => now(),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $member->assignRole('member');

        $this->command->info('Roles and users created/updated successfully!');
    }
}