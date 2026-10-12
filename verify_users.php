<?php
$pdo = new PDO('sqlite:./database/database.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check table info for users
$stmt = $pdo->query('PRAGMA table_info(users)');
$cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "Users columns:\n";
foreach ($cols as $col) {
    if ($col['name'] === 'sacco_id') {
        echo "sacco_id: notnull = {$col['notnull']}, type = {$col['type']}, dflt = {$col['dflt_value']}\n";
    }
}

// Check foreign keys
$stmt = $pdo->query('PRAGMA foreign_key_list(users)');
$fks = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "\nUsers foreign keys:\n";
foreach ($fks as $fk) {
    if ($fk['from'] === 'sacco_id') {
        echo "sacco_id -> {$fk['table']}.{$fk['to']} on_update={$fk['on_update']} on_delete={$fk['on_delete']}\n";
    }
}
