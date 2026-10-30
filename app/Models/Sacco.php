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
        'mobile_money',
        'official_email',
        'physical_address',
        'primary_currency',
        'secondary_currency',
        'status',
        'settings',
        'province',
        'territory',
        'city_quartier',
        'applicant_name',
        'applicant_role',
        'applicant_email',
        'applicant_phone',
        'description'
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
        return $this->attributes['official_email'] ?? null;
    }
}