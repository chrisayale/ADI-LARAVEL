// php artisan make:migration add_loan_disbursement_to_payment_type_enum
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Modify the enum to include 'loan_disbursement'
        DB::statement("ALTER TABLE payment_transactions MODIFY payment_type ENUM('savings_deposit', 'savings_withdrawal', 'loan_repayment', 'penalty', 'loan_disbursement') NOT NULL");
    }

    public function down(): void
    {
        // Revert back to original enum
        DB::statement("ALTER TABLE payment_transactions MODIFY payment_type ENUM('savings_deposit', 'savings_withdrawal', 'loan_repayment', 'penalty') NOT NULL");
    }
};
