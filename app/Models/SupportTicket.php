<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reference_id',
        'subject',
        'category',
        'priority',
        'message',
        'attachment_path',
        'status',
        'resolved_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    /**
     * Get the user who submitted the ticket
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate unique reference ID for ticket
     */
    public static function generateReference()
    {
        return 'SUPPORT-' . strtoupper(uniqid());
    }

    /**
     * Mark ticket as resolved
     */
    public function resolve()
    {
        $this->update([
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);
    }

    /**
     * Mark ticket as in progress
     */
    public function markInProgress()
    {
        $this->update(['status' => 'in_progress']);
    }

    /**
     * Scope for open tickets
     */
    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['open', 'in_progress']);
    }

    /**
     * Scope for high priority tickets
     */
    public function scopeHighPriority($query)
    {
        return $query->where('priority', 'high');
    }

    /**
     * Get human-readable status
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'open' => '🔵 Open',
            'in_progress' => '🟡 In Progress',
            'resolved' => '✅ Resolved',
            default => $this->status,
        };
    }

    /**
     * Get human-readable priority
     */
    public function getPriorityLabelAttribute()
    {
        return match($this->priority) {
            'high' => '🔴 High',
            'medium' => '🟡 Medium',
            'low' => '🔵 Low',
            default => $this->priority,
        };
    }
}
