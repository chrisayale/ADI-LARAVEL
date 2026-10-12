<?php
$pdo = new PDO('sqlite:./database/database.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Disable foreign keys
$pdo->exec('PRAGMA foreign_keys = OFF');

$suffix = '_' . time() . '_' . rand(1000,9999);
$tempTable = 'payment_transactions' . $suffix;

// Drop temp table if exists
$pdo->exec("DROP TABLE IF EXISTS $tempTable");

// Create new table
$pdo->exec("
CREATE TABLE $tempTable (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    transaction_number VARCHAR NOT NULL UNIQUE,
    user_id INTEGER NOT NULL,
    loan_repayment_id INTEGER,
    amount NUMERIC NOT NULL,
    payment_type VARCHAR NOT NULL CHECK (payment_type IN ('savings_deposit', 'savings_withdrawal', 'loan_repayment', 'penalty', 'loan_disbursement')),
    payment_method VARCHAR NOT NULL DEFAULT 'cash' CHECK (payment_method IN ('cash', 'bank_transfer', 'mobile_money')),
    reference_number VARCHAR,
    payment_date DATE NOT NULL,
    receipt_number VARCHAR NOT NULL UNIQUE,
    notes TEXT,
    recorded_by INTEGER,
    status VARCHAR NOT NULL DEFAULT 'completed' CHECK (status IN ('pending', 'completed', 'reversed')),
    created_at DATETIME,
    updated_at DATETIME,
    deleted_at DATETIME,
    sacco_id INTEGER NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (loan_repayment_id) REFERENCES loan_repayments(id) ON DELETE SET NULL,
    FOREIGN KEY (recorded_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (sacco_id) REFERENCES sacos(id) ON DELETE RESTRICT
);
");

// Create indexes
$pdo->exec("CREATE INDEX ${tempTable}_user_id_created_at_index ON $tempTable(user_id, created_at);");
$pdo->exec("CREATE INDEX ${tempTable}_sacco_id_created_at_index ON $tempTable(sacco_id, created_at);");
$pdo->exec("CREATE INDEX ${tempTable}_status_index ON $tempTable(status);");

// Copy data
$pdo->exec("
INSERT INTO $tempTable (id, transaction_number, user_id, loan_repayment_id, amount, payment_type, payment_method, reference_number, payment_date, receipt_number, notes, recorded_by, status, created_at, updated_at, deleted_at, sacco_id)
SELECT id, transaction_number, user_id, loan_repayment_id, amount, payment_type, payment_method, reference_number, payment_date, receipt_number, notes, recorded_by, status, created_at, updated_at, deleted_at, sacco_id
FROM payment_transactions;
");

// Drop old table
$pdo->exec('DROP TABLE payment_transactions;');

// Rename new table to original name
$pdo->exec("ALTER TABLE $tempTable RENAME TO payment_transactions;");

// Re-enable foreign keys
$pdo->exec('PRAGMA foreign_keys = ON');

echo "Done\n";
