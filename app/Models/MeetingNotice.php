<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetingNotice extends Model
{
    protected $fillable = [
        'title',
        'description',
        'meeting_date',
        'meeting_time',
        'venue',
        'agenda',
        'status',
        'created_by',
        'sacco_id'
    ];
}
