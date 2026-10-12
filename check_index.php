<?php
$pdo = new PDO('sqlite:./database/database.sqlite');
$stmt = $pdo->query('SELECT name FROM sqlite_master WHERE type=\"index\" AND name LIKE \"payment_transactions_new%\"');
$rows = $stmt->fetchAll(PDO::FETCH_COLUMN);
print_r($rows);
