<?php
$pdo = new PDO('sqlite:./database/database.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$stmt = $pdo->query("SELECT sql FROM sqlite_master WHERE type='table' AND name='payment_transactions'");
$row = $stmt->fetch(PDO::FETCH_ASSOC);
echo $row['sql'] . "\n";
