<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$txs = App\Models\SavingsTransaction::with('user')->get();
foreach($txs as $tx){
    echo "TX{$tx->id}: user {$tx->user->name} (sacco_id: ".($tx->sacco_id??'null')."), tx sacco_id: ".($tx->sacco_id??'null')."\n";
}
