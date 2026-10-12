<?php
$file = file_get_contents('app/Models/SavingsTransaction.php');
$old = '    protected $fillable = [
        '\''transaction_number'\'',
        '\''user_id'\'',
        '\''amount'\'',
        '\''transaction_type'\'', // Changed from '\''type'\'' to '\''transaction_type'\''
        '\''transaction_date'\'', // ADD THIS - it was missing
        '\''receipt_number'\'',
        '\''notes'\'',
        '\''recorded_by'\'',
        '\''status'\'',
        '\''balance_after'\'' // Changed from '\''balance_before'\'' and '\''balance_after'\'' to just '\''balance_after'\''
    ];';
$new = '    protected $fillable = [
        '\''transaction_number'\'',
        '\''user_id'\'',
        '\''amount'\'',
        '\''transaction_type'\'', // Changed from '\''type'\'' to '\''transaction_type'\''
        '\''transaction_date'\'', // ADD THIS - it was missing
        '\''receipt_number'\'',
        '\''notes'\'',
        '\''recorded_by'\'',
        '\''status'\'',
        '\''balance_after'\'', // Changed from '\''balance_before'\'' and '\''balance_after'\'' to just '\''balance_after'\''
        '\''sacco_id'\''
    ];';
if (strpos($file, $old) === false) {
    // Try alternative spacing
    $old = "    protected \$fillable = [\n        'transaction_number',\n        'user_id',\n        'amount',\n        'transaction_type', // Changed from 'type' to 'transaction_type'\n        'transaction_date', // ADD THIS - it was missing\n        'receipt_number',\n        'notes',\n        'recorded_by',\n        'status',\n        'balance_after' // Changed from 'balance_before' and 'balance_after' to just 'balance_after'\n    ];";
    $new = "    protected \$fillable = [\n        'transaction_number',\n        'user_id',\n        'amount',\n        'transaction_type', // Changed from 'type' to 'transaction_type'\n        'transaction_date', // ADD THIS - it was missing\n        'receipt_number',\n        'notes',\n        'recorded_by',\n        'status',\n        'balance_after', // Changed from 'balance_before' and 'balance_after' to just 'balance_after'\n        'sacco_id'\n    ];";
}
$file = str_replace($old, $new, $file);
file_put_contents('app/Models/SavingsTransaction.php', $file);
echo "Fixed SavingsTransaction fillable\n";
