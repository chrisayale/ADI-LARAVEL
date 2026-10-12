<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\SavingsTransaction;
use Carbon\Carbon;

$user1 = App\Models\User::find(1);

$tx = SavingsTransaction::create([
    'user_id' => $user1->id,
    'amount' => 50.00,
    'transaction_type' => 'deposit',
    'transaction_date' => Carbon::today(),
    'receipt_number' => 'RX004',
    'notes' => 'Test tx for user1 after sacco null',
    'recorded_by' => $user1->id,
    'status' => 'approved',
    'sacco_id' => $user1->sacco_id, // should be null
    'balance_after' => 50.00,
]);

echo "Created transaction for user1: id {$tx->id}, sacco_id {$tx->sacco_id}\n";
