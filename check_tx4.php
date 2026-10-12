<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$tx = App\Models\SavingsTransaction::find(4);
echo 'TX4 sacco_id: ' . ($tx->sacco_id ?? 'null') . "\n";
