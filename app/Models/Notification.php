<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    /**
     * Get the user that owns the notification
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(): void
    {
        if (! $this->read_at) {
            $this->update(['read_at' => now()]);
        }
    }

    /**
     * Check if notification is unread
     */
    public function isUnread(): bool
    {
        return $this->read_at === null;
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
     * Get icon for notification type
     */
    public function getIconAttribute(): string
    {
        return match ($this->type) {
            'low_stock' => 'bi-exclamation-triangle-fill text-warning',
            'daily_summary' => 'bi-calendar-check text-info',
            'system_update' => 'bi-gear text-primary',
            'user_activity' => 'bi-person text-success',
            'payment_request' => 'bi-wallet2 text-warning',
            'payment_approved' => 'bi-check-circle-fill text-success',
            'payment_rejected' => 'bi-x-circle-fill text-danger',
            default => 'bi-bell text-secondary',
        };
    }
}
