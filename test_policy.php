<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\SavingsTransaction;
use App\Support\TenantContext;
use App\Policies\SavingsTransactionPolicy;

// Get users and transactions
$user1 = App\Models\User::find(1); // Super Admin
$user2 = App\Models\User::find(2); // Treasurer
$user3 = App\Models\User::find(3); // Member

$tx1 = App\Models\SavingsTransaction::find(1);
$tx2 = App\Models\SavingsTransaction::find(2);
$tx3 = App\Models\SavingsTransaction::find(3);

$policy = new SavingsTransactionPolicy();

echo "=== Test 1: Super Admin with no context ===\n";
TenantContext::clear();
echo "TenantContext has? " . (TenantContext::has() ? 'yes' : 'no') . "\n";
echo "user1 viewAny: " . ($policy->viewAny($user1) ? 'true' : 'false') . "\n";
echo "user1 view tx1: " . ($policy->view($user1, $tx1) ? 'true' : 'false') . "\n";
echo "user1 view tx2: " . ($policy->view($user1, $tx2) ? 'true' : 'false') . "\n";
echo "user1 view tx3: " . ($policy->view($user1, $tx3) ? 'true' : 'false') . "\n";
echo "\n";

echo "=== Test 2: Super Admin with context SACCO 1 (default) ===\n";
$sacco1 = App\Models\Sacco::find(1);
TenantContext::set($sacco1);
echo "TenantContext has? " . (TenantContext::has() ? 'yes' : 'no') . "\n";
if (TenantContext::has()) {
    echo "TenantContext id: " . TenantContext::id() . "\n";
}
echo "user1 viewAny: " . ($policy->viewAny($user1) ? 'true' : 'false') . "\n";
echo "user1 view tx1 (sacco_id null): " . ($policy->view($user1, $tx1) ? 'true' : 'false') . "\n";
echo "user1 view tx2 (sacco_id 1): " . ($policy->view($user1, $tx2) ? 'true' : 'false') . "\n";
echo "user1 view tx3 (sacco_id 2): " . ($policy->view($user1, $tx3) ? 'true' : 'false') . "\n";
echo "\n";

echo "=== Test 3: Super Admin with context SACCO 2 (Umoja) ===\n";
$sacco2 = App\Models\Sacco::find(2);
TenantContext::set($sacco2);
echo "TenantContext has? " . (TenantContext::has() ? 'yes' : 'no') . "\n";
if (TenantContext::has()) {
    echo "TenantContext id: " . TenantContext::id() . "\n";
}
echo "user1 viewAny: " . ($policy->viewAny($user1) ? 'true' : 'false') . "\n";
echo "user1 view tx1: " . ($policy->view($user1, $tx1) ? 'true' : 'false') . "\n";
echo "user1 view tx2: " . ($policy->view($user1, $tx2) ? 'true' : 'false') . "\n";
echo "user1 view tx3: " . ($policy->view($user1, $tx3) ? 'true' : 'false') . "\n";
echo "\n";

echo "=== Test 4: Regular user (user2) in SACCO 1, no context? (should have context from resolver) ===\n";
// For regular user, the middleware sets context to user's sacco.
// Let's simulate that by setting context to user2's sacco.
TenantContext::set($user2->sacco);
echo "TenantContext has? " . (TenantContext::has() ? 'yes' : 'no') . "\n";
if (TenantContext::has()) {
    echo "TenantContext id: " . TenantContext::id() . "\n";
}
echo "user2 viewAny: " . ($policy->viewAny($user2) ? 'true' : 'false') . "\n";
echo "user2 view tx1: " . ($policy->view($user2, $tx1) ? 'true' : 'false') . "\n";
echo "user2 view tx2: " . ($policy->view($user2, $tx2) ? 'true' : 'false') . "\n";
echo "user2 view tx3: " . ($policy->view($user2, $tx3) ? 'true' : 'false') . "\n";
echo "\n";

echo "=== Test 5: Regular user (user3) in SACCO 2 ===\n";
TenantContext::set($user3->sacco);
echo "TenantContext has? " . (TenantContext::has() ? 'yes' : 'no') . "\n";
if (TenantContext::has()) {
    echo "TenantContext id: " . TenantContext::id() . "\n";
}
echo "user3 viewAny: " . ($policy->viewAny($user3) ? 'true' : 'false') . "\n";
echo "user3 view tx1: " . ($policy->view($user3, $tx1) ? 'true' : 'false') . "\n";
echo "user3 view tx2: " . ($policy->view($user3, $tx2) ? 'true' : 'false') . "\n";
echo "user3 view tx3: " . ($policy->view($user3, $tx3) ? 'true' : 'false') . "\n";
echo "\n";

echo "=== Test 6: Regular user viewing transaction of another user but same SACCO ===\n";
// Create a transaction for user2 but we already have tx2 (user2 sacco 1)
// Let's create another transaction for user2 but we can test view of tx2 by user3? Should be false because sacco_id mismatch.
// Already covered.

echo "Done.\n";
