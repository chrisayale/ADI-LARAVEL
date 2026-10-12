<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

// Create super_admin role if not exists
$role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'super_admin']);
echo "Role super_admin id: {$role->id}\n";

// Assign role to user 1 (System Administrator)
$user = App\Models\User::find(1);
$user->assignRole('super_admin');
echo "Assigned super_admin role to user {$user->id}\n";

// Optionally, we can also keep admin role; not needed.

echo "Done.\n";
