<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestSacco extends Model
{
    use SoftDeletes;

    protected $table = 'request_sacos';

    protected $fillable = [
        'name',
        'code',
        'registration_number',
        'address',
        'phone_number',
        'email',
        'status',
        'requested_by',
        'reviewed_by',
        'reviewed_at',
        'settings',
        'province',
        'territory',
        'city_village',
        'applicant_name',
        'applicant_role',
        'applicant_email',
        'applicant_phone',
        'description',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'settings' => 'array',
        'requested_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get the SACCO associated with this request (based on code).
     */
    public function getSaccoAttribute()
    {
        if (!empty($this->code)) {
            return Sacco::where('code', $this->code)->first();
        }
        return null;
    }

    /**
     * Get the official user account associated with this request's SACCO.
     */
    public function getOfficialUserAttribute()
    {
        $sacco = $this->sacco;
        if ($sacco) {
            return User::where('sacco_id', $sacco->id)
                ->where('name', 'Compte Officiel - ' . $sacco->name)
                ->first();
        }
        return null;
    }
}
