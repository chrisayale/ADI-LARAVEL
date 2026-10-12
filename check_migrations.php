<?php
$db = new PDO('sqlite:database/database.sqlite');
$stmt = $db->query('SELECT * FROM migrations ORDER BY id DESC');
$result = $stmt->fetchAll();
foreach ($result as $row) {
    echo $row['migration'] . " - " . $row['batch'] . "\n";
}
