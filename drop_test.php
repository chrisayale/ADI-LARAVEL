<?php
$db = new PDO('sqlite:database/database.sqlite');
try {
    $db->exec('DROP TABLE IF EXISTS loan_applications');
    echo 'Table dropped successfully\n';
} catch (Exception $e) {
    echo 'Error dropping table: ' . $e->getMessage() . "\n";
}
