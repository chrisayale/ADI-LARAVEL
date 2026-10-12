<?php
$db = new PDO('sqlite:database/database.sqlite');
$result = $db->query("SELECT * FROM migrations WHERE migration LIKE '%make_users_sacco_id_nullable%'");
$rows = $result->fetchAll();
foreach ($rows as $row) {
    echo "Migration: " . $row['migration'] . ", Batch: " . $row['batch'] . PHP_EOL;
}
