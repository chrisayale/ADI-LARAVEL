<?php
$db = new PDO('sqlite:database/database.sqlite');
$result = $db->query("SELECT MAX(batch) as max_batch FROM migrations");
$row = $result->fetch();
echo $row['max_batch'];
