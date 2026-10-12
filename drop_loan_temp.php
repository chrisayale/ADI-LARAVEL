<?php
$db = new PDO('sqlite:database/database.sqlite');
$db->exec("DROP TABLE IF EXISTS loan_applications_new");
echo "Dropped loan_applications_new table if it existed." . PHP_EOL;
