<?php
$db = new PDO('sqlite:database/database.sqlite');
$result = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='users_new'");
$tables = $result->fetchAll();
echo "Tables found: ";
foreach ($tables as $table) {
    echo $table['name'] . " ";
}
echo PHP_EOL;
