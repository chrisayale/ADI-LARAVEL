<?php
$db = new PDO('sqlite:database/database.sqlite');
$db->exec("DROP TABLE IF EXISTS payment_transactions_new");
echo "Dropped payment_transactions_new table if it existed." . PHP_EOL;
