<?php
$db = new PDO('sqlite:database/database.sqlite');
$result = $db->query("PRAGMA table_info(payment_transactions)");
$columns = $result->fetchAll();
foreach ($columns as $col) {
    echo $col['name'] . ' - ' . $col['type'] . ' - ' . ($col['notnull'] ? 'NOT NULL' : 'NULL') . ' - ' . $col['dflt_value'] . PHP_EOL;
}
