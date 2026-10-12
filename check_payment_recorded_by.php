<?php
$db = new PDO('sqlite:database/database.sqlite');
// Check column nullable
$result = $db->query("PRAGMA table_info(payment_transactions)");
$columns = $result->fetchAll();
foreach ($columns as $col) {
    if ($col['name'] == 'recorded_by') {
        echo "recorded_by: type={$col['type']}, notnull={$col['notnull']}, default={$col['dflt_value']}" . PHP_EOL;
    }
}
// Check foreign key constraints (SQLite doesn't store them in PRAGMA foreign_key_list? Actually it does)
$result = $db->query("PRAGMA foreign_key_list(payment_transactions)");
$fk = $result->fetchAll();
foreach ($fk as $f) {
    if ($f['from'] == 'recorded_by') {
        echo "Foreign key on recorded_by: table={$f['table']}, on_delete={$f['on_delete']}" . PHP_EOL;
    }
}
