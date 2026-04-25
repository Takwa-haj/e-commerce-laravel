<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'comment',
        'read',
        'user_id', // Add this column via migration
        'ip_address',
        'user_agent',
         'admin_reply',
        'replied_at'
    ];

    protected $casts = [
        'read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
            'replied_at' => 'datetime'

    ];

    /**
     * Get the user that owns the contact message (if user is logged in)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include unread messages
     */
    public function scopeUnread($query)
    {
        return $query->where('read', false);
    }

    /**
     * Scope a query to only include read messages
     */
    public function scopeRead($query)
    {
        return $query->where('read', true);
    }

    /**
     * Mark the message as read
     */
    public function markAsRead()
    {
        $this->read = true;
        $this->save();
    }

    /**
     * Get formatted created date
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d M Y, H:i');
    }

    /**
     * Get short comment preview
     */
    public function getCommentPreviewAttribute($length = 100)
    {
        return strlen($this->comment) > $length
            ? substr($this->comment, 0, $length) . '...'
            : $this->comment;
    }
}
