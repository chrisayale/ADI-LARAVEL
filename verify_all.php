<?php
$pdo = new PDO('sqlite:./database/database.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check payment_transactions.sacco_id fk
$stmt = $pdo->query('PRAGMA foreign_key_list(payment_transactions)');
$fks = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "Payment transactions foreign keys:\n";
foreach ($fks as $fk) {
    echo "{$fk['from']} -> {$fk['table']}.{$fk['to']} on_delete={$fk['on_delete']}\n";
}

// Check payment_type enum values (just see if loan_disbursement is allowed)
$stmt = $pdo->query("SELECT DISTINCT payment_type FROM payment_transactions LIMIT 10");
$types = $stmt->fetchAll(PDO::FETCH_COLUMN);
echo "\nDistinct payment_type values present:\n";
print_r($types);

// Check loan_repayment_id fk (should be SET NULL)
foreach ($fks as $fk) {
    if ($fk['from'] === 'loan_repayment_id') {
        echo "\nloan_repayment_id -> {$fk['table']}.{$fk['to']} on_delete={$fk['on_delete']}\n";
    }
}

// Check sacco_id fk (should be RESTRICT)
foreach ($fks as $fk) {
    if ($fk['from'] === 'sacco_id') {
        echo "sacco_id -> {$fk['table']}.{$fk['to']} on_delete={$fk['on_delete']}\n";
    }
}
