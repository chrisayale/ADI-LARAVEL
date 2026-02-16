<?php
// app/Models/MemberNotification.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sender_id',
        'title',
        'message',
        'type',
        'data',
        'read_at'
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime'
    ];

    /**
     * Get the recipient user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the sender (admin/treasurer who sent the notification)
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Scope to get unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope to get read notifications
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Scope to get notifications by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        if (is_null($this->read_at)) {
            $this->update(['read_at' => now()]);
        }
        return $this;
    }

    /**
     * Check if notification is read
     */
    public function isRead()
    {
        return !is_null($this->read_at);
    }

    /**
     * Get notification icon based on type
     */
    public function getIconAttribute()
    {
        return match($this->type) {
            'admin_message' => 'fa-envelope',
            'loan_update' => 'fa-hand-holding-usd',
            'savings_update' => 'fa-piggy-bank',
            'meeting' => 'fa-calendar-alt',
            'system' => 'fa-info-circle',
            default => 'fa-bell'
        };
    }

    /**
     * Get notification color based on type
     */
    public function getColorAttribute()
    {
        return match($this->type) {
            'admin_message' => 'primary',
            'loan_update' => 'success',
            'savings_update' => 'info',
            'meeting' => 'warning',
            'system' => 'secondary',
            default => 'primary'
        };
    }
}
