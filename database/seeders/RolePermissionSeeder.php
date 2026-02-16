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

        // Create permissions
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
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create roles
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->givePermissionTo(Permission::all());

        $treasurerRole = Role::create(['name' => 'treasurer', 'guard_name' => 'web']);
        $treasurerRole->givePermissionTo([
            'access treasurer dashboard',
            'view members',
            'view savings',
            'record deposits',
            'record withdrawals',
            'view loans',
            'verify loans',
        ]);

        $memberRole = Role::create(['name' => 'member', 'guard_name' => 'web']);
        $memberRole->givePermissionTo([
            'access member dashboard',
            'view savings',
            'apply for loans',
            'view loans',
        ]);

        // Create users
        $admin = User::create([
            'name' => 'System Administrator',
            'email' => 'admin@save.com',
            'password' => Hash::make('password'),
            'phone_number' => '+256700000001',
            'address' => 'Kampala',
            'national_id' => 'ADMIN001',
            'membership_number' => 'ADMIN-2024-001',
            'membership_date' => now(),
            'status' => 'active',
        ]);
        $admin->assignRole('admin');

        $treasurer = User::create([
            'name' => 'John Treasurer',
            'email' => 'treasurer@save.com',
            'password' => Hash::make('password'),
            'phone_number' => '+256700000002',
            'address' => 'Kampala',
            'national_id' => 'TREAS001',
            'membership_number' => 'TREAS-2024-001',
            'membership_date' => now(),
            'status' => 'active',
        ]);
        $treasurer->assignRole('treasurer');

        $member = User::create([
            'name' => 'Alice Member',
            'email' => 'alice@save.com',
            'password' => Hash::make('password'),
            'phone_number' => '+256700000003',
            'address' => 'Kampala',
            'national_id' => 'MEM001',
            'membership_number' => 'MEM-2024-001',
            'membership_date' => now(),
            'status' => 'active',
        ]);
        $member->assignRole('member');

        $this->command->info('Roles and users created successfully!');
    }
}
