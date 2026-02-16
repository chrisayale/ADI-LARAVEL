<?php
// app/Models/LoanGuarantor.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanGuarantor extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_application_id',
        'name',
        'phone',
        'email',
        'id_number',
        'occupation',
        'address',
        'relationship',
        'status',
        'verification_notes',
        'verified_by',
        'verified_at'
    ];

    protected $casts = [
        'verified_at' => 'datetime'
    ];

    public function loanApplication()
    {
        return $this->belongsTo(LoanApplication::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
