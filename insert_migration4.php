<?php
$db = new PDO('sqlite:database/database.sqlite');
// Get max batch
$result = $db->query("SELECT MAX(batch) as max_batch FROM migrations");
$row = $result->fetch();
$next_batch = (int)$row['max_batch'] + 1;
$migration_name = '2026_10_08_093500_fix_loan_applications_sacco_id_foreign_key';
$now = date('Y-m-d H:i:s');
$db->exec("INSERT INTO migrations (migration, batch) VALUES ('$migration_name', $next_batch)");
echo "Inserted migration $migration_name with batch $next_batch" . PHP_EOL;
