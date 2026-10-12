<?php
$pdo = new PDO('sqlite:./database/database.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check table info
$stmt = $pdo->query('PRAGMA table_info(payment_transactions)');
$cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "Columns:\n";
foreach ($cols as $col) {
    if ($col['name'] === 'recorded_by') {
        echo "recorded_by: notnull = {$col['notnull']}, type = {$col['type']}, dflt = {$col['dflt_value']}\n";
    }
}

// Check foreign keys
$stmt = $pdo->query('PRAGMA foreign_key_list(payment_transactions)');
$fks = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "\nForeign keys:\n";
foreach ($fks as $fk) {
    if ($fk['from'] === 'recorded_by') {
        echo "recorded_by -> {$fk['table']}.{$fk['to']} on_update={$fk['on_update']} on_delete={$fk['on_delete']}\n";
    }
}
