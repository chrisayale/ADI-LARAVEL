<?php
$db = new PDO('sqlite:database/database.sqlite');
$result = $db->query("SELECT name FROM sqlite_master WHERE type='table'");
$tables = $result->fetchAll();
echo "Tables:" . PHP_EOL;
foreach ($tables as $table) {
    echo $table['name'] . PHP_EOL;
}
