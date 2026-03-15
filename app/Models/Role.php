<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = [
        'name',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get users associated with this role
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'role_id');
    }

    /**
     * Get user count for this role
     */
    public function getUserCount(): int
    {
        return $this->users()->count();
    }

    /**
     * Check if role is system role
     */
    public function isSystemRole(): bool
    {
        return in_array($this->id, [1, 2, 3]);
    }
}
