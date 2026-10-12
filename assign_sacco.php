<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Get users
$user1 = App\Models\User::find(1);
$user2 = App\Models\User::find(2);
$user3 = App\Models\User::find(3);

// Get saccos
$sacco1 = App\Models\Sacco::first(); // default
$sacco2 = App\Models\Sacco::where('code', 'UOJA')->first(); // Umoja

echo "Assigning:\n";
echo "User 1 ($user1->name) sacco_id: null (Super Admin)\n";
$user1->sacco_id = null;
$user1->save();

echo "User 2 ($user2->name) sacco_id: " . $sacco1->id . " (" . $sacco1->name . ")\n";
$user2->sacco_id = $sacco1->id;
$user2->save();

echo "User 3 ($user3->name) sacco_id: " . $sacco2->id . " (" . $sacco2->name . ")\n";
$user3->sacco_id = $sacco2->id;
$user3->save();

echo "Done.\n";
