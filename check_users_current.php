<?php
$db = new PDO('sqlite:database/database.sqlite');
$stmt = $db->query('SELECT sql FROM sqlite_master WHERE type="table" AND name="users"');
$result = $stmt->fetchAll();
foreach ($result as $row) {
    echo $row[0] . "\n";
}
