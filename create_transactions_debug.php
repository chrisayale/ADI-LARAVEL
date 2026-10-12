<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\SavingsTransaction;
use Carbon\Carbon;

// Clear existing transactions for clean test
SavingsTransaction::truncate();

// Get users
$user1 = App\Models\User::find(1);
$user2 = App\Models\User::find(2);
$user3 = App\Models\User::find(3);

echo "User sacco_ids before creating transactions:\n";
echo "User1: ".($user1->sacco_id ?? 'null')."\n";
echo "User2: ".($user2->sacco_id ?? 'null')."\n";
echo "User3: ".($user3->sacco_id ?? 'null')."\n";

// Get saccos
$sacco1 = App\Models\Sacco::find(1); // default
$sacco2 = App\Models\Sacco::find(2); // Umoja

echo "Creating transactions...\n";

// Create a transaction for each user
$tx1 = SavingsTransaction::create([
    'user_id' => $user1->id,
    'amount' => 100.00,
    'transaction_type' => 'deposit',
    'transaction_date' => Carbon::today(),
    'receipt_number' => 'RX001',
    'notes' => 'Test tx for user1',
    'recorded_by' => $user1->id,
    'status' => 'approved',
    'sacco_id' => $user1->sacco_id, // will be null
    'balance_after' => 100.00,
]);

$tx2 = SavingsTransaction::create([
    'user_id' => $user2->id,
    'amount' => 200.00,
    'transaction_type' => 'deposit',
    'transaction_date' => Carbon::today(),
    'receipt_number' => 'RX002',
    'notes' => 'Test tx for user2',
    'recorded_by' => $user2->id,
    'status' => 'approved',
    'sacco_id' => $user2->sacco_id, // 1
    'balance_after' => 200.00,
]);

$tx3 = SavingsTransaction::create([
    'user_id' => $user3->id,
    'amount' => 150.00,
    'transaction_type' => 'deposit',
    'transaction_date' => Carbon::today(),
    'receipt_number' => 'RX003',
    'notes' => 'Test tx for user3',
    'recorded_by' => $user3->id,
    'status' => 'approved',
    'sacco_id' => $user3->sacco_id, // 2
    'balance_after' => 150.00,
]);

echo "Created transactions:\n";
echo "TX1: id {$tx1->id}, user {$tx1->user_id}, sacco_id {$tx1->sacco_id}\n";
echo "TX2: id {$tx2->id}, user {$tx2->user_id}, sacco_id {$tx2->sacco_id}\n";
echo "TX3: id {$tx3->id}, user {$tx3->user_id}, sacco_id {$tx3->sacco_id}\n";

echo "Done.\n";
