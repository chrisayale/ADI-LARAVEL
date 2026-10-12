<?php
$db = new PDO('sqlite:database/database.sqlite');
$stmt = $db->query('PRAGMA foreign_key_list(loan_applications)');
$result = $stmt->fetchAll();
foreach ($result as $row) {
    print_r($row);
    echo "\n";
}
