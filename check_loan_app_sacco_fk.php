<?php
$db = new PDO('sqlite:database/database.sqlite');
$stmt = $db->query('SELECT sql FROM sqlite_master WHERE type="table" AND name="loan_applications"');
$result = $stmt->fetchAll();
foreach ($result as $row) {
    if (strpos($row[0], 'sacco_id') !== false) {
        echo "Full table definition:\n";
        echo $row[0] . "\n\n";
    }
}
