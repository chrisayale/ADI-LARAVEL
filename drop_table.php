<?php
$db = new PDO('sqlite:database/database.sqlite');
$db->exec("DROP TABLE IF EXISTS users_new");
echo "Dropped users_new table if it existed." . PHP_EOL;
