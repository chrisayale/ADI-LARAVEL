<?php
$db = new PDO('sqlite:database/database.sqlite');
try {
    // Try to add the foreign key constraint
    $db->exec('ALTER TABLE loan_applications ADD CONSTRAINT fk_loan_applications_sacco_id FOREIGN KEY (sacco_id) REFERENCES sacos(id) ON DELETE RESTRICT');
    echo "Foreign key added successfully\n";
} catch (Exception $e) {
    echo "Error adding foreign key: " . $e->getMessage() . "\n";
}
