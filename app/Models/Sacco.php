<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sacco extends Model
{
    protected $table = 'sacos';
    protected $fillable = [
        'name',
        'code',
        'registration_number',
        'address',
        'phone',
        'email',
        'primary_currency',
        'secondary_currency',
        'status',
        'settings'
    ];

    protected $casts = [
        'status' => 'string',
        'settings' => 'array',
    ];

    /**
     * Get all users belonging to this SACCO.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Check if SACCO is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Get the official email for the SACCO.
     */
    public function getOfficialEmailAttribute()
    {
        return $this->email;
    }
}
