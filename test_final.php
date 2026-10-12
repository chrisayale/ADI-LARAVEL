<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\SavingsTransaction;
use App\Support\TenantContext;
use App\Policies\SavingsTransactionPolicy;

// Get users
$user1 = App\Models\User::find(1); // Super Admin, sacco_id null, roles admin+super_admin
$user2 = App\Models\User::find(2); // Treasurer, sacco_id 1
$user3 = App\Models\User::find(3); // Member, sacco_id 2

// Get transactions
$tx1 = App\Models\SavingsTransaction::find(1); // user1 sacco_id 1 (old)
$tx2 = App\Models\SavingsTransaction::find(2); // user2 sacco_id 1
$tx3 = App\Models\SavingsTransaction::find(3); // user3 sacco_id 2
$tx4 = App\Models\SavingsTransaction::find(4); // user1 sacco_id null (new)

$policy = new SavingsTransactionPolicy();

echo "=== Test 1: Super Admin (user1) with sacco_id null, no context ===\n";
TenantContext::clear();
echo "TenantContext has? " . (TenantContext::has() ? 'yes' : 'no') . "\n";
echo "user1 viewAny: " . ($policy->viewAny($user1) ? 'true' : 'false') . "\n";
echo "user1 view tx1 (sacco_id 1): " . ($policy->view($user1, $tx1) ? 'true' : 'false') . "\n";
echo "user1 view tx2 (sacco_id 1): " . ($policy->view($user1, $tx2) ? 'true' : 'false') . "\n";
echo "user1 view tx3 (sacco_id 2): " . ($policy->view($user1, $tx3) ? 'true' : 'false') . "\n";
echo "user1 view tx4 (sacco_id null): " . ($policy->view($user1, $tx4) ? 'true' : 'false') . "\n";
echo "\n";

echo "=== Test 2: Super Admin with context SACCO 1 (default) ===\n";
$sacco1 = App\Models\Sacco::find(1);
TenantContext::set($sacco1);
echo "TenantContext has? " . (TenantContext::has() ? 'yes' : 'no') . "\n";
if (TenantContext::has()) {
    echo "TenantContext id: " . TenantContext::id() . "\n";
}
echo "user1 viewAny: " . ($policy->viewAny($user1) ? 'true' : 'false') . "\n";
echo "user1 view tx1 (sacco_id 1): " . ($policy->view($user1, $tx1) ? 'true' : 'false') . "\n";
echo "user1 view tx2 (sacco_id 1): " . ($policy->view($user1, $tx2) ? 'true' : 'false') . "\n";
echo "user1 view tx3 (sacco_id 2): " . ($policy->view($user1, $tx3) ? 'true' : 'false') . "\n";
echo "user1 view tx4 (sacco_id null): " . ($policy->view($user1, $tx4) ? 'true' : 'false') . "\n";
echo "\n";

echo "=== Test 3: Super Admin with context SACCO 2 (Umoja) ===\n";
$sacco2 = App\Models\Sacco::find(2);
TenantContext::set($sacco2);
echo "TenantContext has? " . (TenantContext::has() ? 'yes' : 'no') . "\n";
if (TenantContext::has()) {
    echo "TenantContext id: " . TenantContext::id() . "\n";
}
echo "user1 viewAny: " . ($policy->viewAny($user1) ? 'true' : 'false') . "\n";
echo "user1 view tx1 (sacco_id 1): " . ($policy->view($user1, $tx1) ? 'true' : 'false') . "\n";
echo "user1 view tx2 (sacco_id 1): " . ($policy->view($user1, $tx2) ? 'true' : 'false') . "\n";
echo "user1 view tx3 (sacco_id 2): " . ($policy->view($user1, $tx3) ? 'true' : 'false') . "\n";
echo "user1 view tx4 (sacco_id null): " . ($policy->view($user1, $tx4) ? 'true' : 'false') . "\n";
echo "\n";

echo "=== Test 4: Super Admin with context cleared again ===\n";
TenantContext::clear();
echo "TenantContext has? " . (TenantContext::has() ? 'yes' : 'no') . "\n";
echo "user1 viewAny: " . ($policy->viewAny($user1) ? 'true' : 'false') . "\n";
echo "user1 view tx1: " . ($policy->view($user1, $tx1) ? 'true' : 'false') . "\n";
echo "user1 view tx2: " . ($policy->view($user1, $tx2) ? 'true' : 'false') . "\n";
echo "user1 view tx3: " . ($policy->view($user1, $tx3) ? 'true' : 'false') . "\n";
echo "user1 view tx4: " . ($policy->view($user1, $tx4) ? 'true' : 'false') . "\n";
echo "\n";

echo "=== Test 5: Regular user (user2) in SACCO 1 ===\n";
// For regular user, the middleware sets context to user's sacco.
// We'll simulate that by setting context to user2's sacco.
TenantContext::set($user2->sacco);
echo "TenantContext has? " . (TenantContext::has() ? 'yes' : 'no') . "\n";
if (TenantContext::has()) {
    echo "TenantContext id: " . TenantContext::id() . "\n";
}
echo "user2 viewAny: " . ($policy->viewAny($user2) ? 'true' : 'false') . "\n";
echo "user2 view tx1 (sacco_id 1): " . ($policy->view($user2, $tx1) ? 'true' : 'false') . "\n";
echo "user2 view tx2 (sacco_id 1): " . ($policy->view($user2, $tx2) ? 'true' : 'false') . "\n";
echo "user2 view tx3 (sacco_id 2): " . ($policy->view($user2, $tx3) ? 'true' : 'false') \n";
echo "user2 view tx4 (sacco_id null): " . ($policy->view($user2, $tx4) ? 'true' : 'false') . "\n";
echo "\n";

echo "=== Test 6: Regular user (user3) in SACCO 2 ===\n";
TenantContext::set($user3->sacco);
echo "TenantContext has? " . (TenantContext::has() ? 'yes' : 'no') . "\n";
if (TenantContext::has()) {
    echo "TenantContext id: " . TenantContext::id() . "\n";
}
echo "user3 viewAny: " . ($policy->viewAny($user3) ? 'true' : 'false') . "\n";
echo "user3 view tx1 (sacco_id 1): " . ($policy->view($user3, $tx1) ? 'true' : 'false') . "\n";
echo "user3 view tx2 (sacco_id 1): " . ($policy->view($user3, $tx2) ? 'true' : 'false') . "\n";
echo "user3 view tx3 (sacco_id 2): " . ($policy->view($user3, $tx3) ? 'true' : 'false') \n";
echo "user3 view tx4 (sacco_id null): " . ($policy->view($user3, $tx4) ? 'true' : 'false') . "\n";
echo "\n";

echo "Done.\n";
