<?php
// app/Models/LoanDocument.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoanDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'document_number',
        'loan_application_id',
        'uploaded_by',
        'document_name',
        'original_name',
        'file_path',
        'file_type',
        'file_size',
        'mime_type',
        'category',
        'guarantor_id',
        'description',
        'verification_status',
        'verified_by',
        'verified_at',
        'verification_notes'
    ];

    protected $casts = [
        'file_size' => 'integer',
        'verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function loanApplication()
    {
        return $this->belongsTo(LoanApplication::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function guarantor()
    {
        return $this->belongsTo(LoanGuarantor::class);
    }

    // Helper to get formatted file size
    public function getFormattedSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    // Helper to get file icon class
    public function getFileIconAttribute()
    {
        $extension = strtolower($this->file_type);
        $icons = [
            'pdf' => 'fa-file-pdf',
            'jpg' => 'fa-file-image',
            'jpeg' => 'fa-file-image',
            'png' => 'fa-file-image',
            'gif' => 'fa-file-image',
            'doc' => 'fa-file-word',
            'docx' => 'fa-file-word',
            'xls' => 'fa-file-excel',
            'xlsx' => 'fa-file-excel',
            'txt' => 'fa-file-alt',
        ];

        return $icons[$extension] ?? 'fa-file';
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('verification_status', 'pending');
    }

    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }

    public function scopeRejected($query)
    {
        return $query->where('verification_status', 'rejected');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeForGuarantor($query, $guarantorId)
    {
        return $query->where('guarantor_id', $guarantorId);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($document) {
            // Generate unique document number
            $document->document_number = 'DOC-' . date('Y') . '-' . str_pad(static::max('id') + 1, 6, '0', STR_PAD_LEFT);
        });
    }

    public function downloadDocument(LoanDocument $document)
{
    // Check if user has permission to view this document
    $loan = $document->loanApplication;

    if (!$loan) {
        abort(404, 'Loan not found');
    }

    // Check authorization
    if (auth()->hasRole('admin')) {
        // Admin can view all documents
    } elseif (auth()->user()->hasRole('treasurer') && $loan->treasurer_id == auth()->id()) {
        // Assigned treasurer can view documents
    } elseif (auth()->user()->hasRole('member') && $loan->user_id == auth()->id()) {
        // Member can view their own loan documents
    } else {
        abort(403, 'Unauthorized access to document');
    }

    $path = storage_path('app/public/' . $document->file_path);

    if (!file_exists($path)) {
        abort(404, 'File not found');
    }

    return response()->download($path, $document->original_name);
}
}
