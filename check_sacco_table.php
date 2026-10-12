<?php
$db = new PDO('sqlite:database/database.sqlite');
$result = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='sacos'");
$row = $result->fetch();
if ($row) {
    echo "Table sacos exists" . PHP_EOL;
} else {
    echo "Table sacos does not exist" . PHP_EOL;
}
