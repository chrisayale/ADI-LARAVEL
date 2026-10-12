<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$user = App\Models\User::find(1);
echo "Before: " . ($user->sacco_id ?? 'null') . "\n";
$user->sacco_id = 1;
$user->save();
echo "After: " . ($user->sacco_id ?? 'null') . "\n";
