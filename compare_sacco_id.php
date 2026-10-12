<?php
$db = new PDO('sqlite:database/database.sqlite');

// Check users table sacco_id
$stmt = $db->query("SELECT sql FROM sqlite_master WHERE type='table' AND name='users'");
$result = $stmt->fetchAll();
echo "USERS TABLE:\n";
foreach ($result as $row) {
    if (preg_match('/sacco_id[^,)]*/i', $row[0], $matches)) {
        echo "sacco_id: " . $matches[0] . "\n";
    }
}

echo "\n";

// Check loan_applications table sacco_id
$stmt = $db->query("SELECT sql FROM sqlite_master WHERE type='table' AND name='loan_applications'");
$result = $stmt->fetchAll();
echo "LOAN_APPLICATIONS TABLE:\n";
foreach ($result as $row) {
    if (preg_match('/sacco_id[^,)]*/i', $row[0], $matches)) {
        echo "sacco_id: " . $matches[0] . "\n";
    }
}
