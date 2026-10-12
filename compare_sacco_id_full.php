<?php
$db = new PDO('sqlite:database/database.sqlite');

// Get full column info for users table
$stmt = $db->query("SELECT sql FROM sqlite_master WHERE type='table' AND name='users'");
$result = $stmt->fetchAll();
echo "USERS TABLE FULL DEFINITION:\n";
foreach ($result as $row) {
    echo $row[0] . "\n";
}

echo "\nLOAN_APPLICATIONS TABLE FULL DEFINITION:\n";
$stmt = $db->query("SELECT sql FROM sqlite_master WHERE type='table' AND name='loan_applications'");
$result = $stmt->fetchAll();
foreach ($result as $row) {
    echo $row[0] . "\n";
}
