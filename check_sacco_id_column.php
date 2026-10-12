<?php
$db = new PDO('sqlite:database/database.sqlite');
$stmt = $db->query("SELECT sql FROM sqlite_master WHERE type='table' AND name='loan_applications'");
$result = $stmt->fetchAll();
foreach ($result as $row) {
    if (preg_match('/sacco_id/i', $row[0])) {
        echo "Found sacco_id in table definition:\n";
        echo $row[0] . "\n";
        // Extract just the sacco_id column definition
        if (preg_match('/sacco_id[^,)]*/i', $row[0], $matches)) {
            echo "sacco_id column: " . $matches[0] . "\n";
        }
    }
}
