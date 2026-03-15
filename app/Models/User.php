<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes; 
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes; 

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'uuid',
        'name',
        'email',
        'phone',
        'country',
        'city',
        'password',
        'country_code',
        'phone_number',
        'role_id',
        'otp',
        'profile_photo',
        'bio',
        'is_active',
        'is_default_pin',
        'last_login_at',
        'last_login_ip',
        'previous_login_at',
        'total_login_count',
        'total_session_minutes',
        'last_activity_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',

    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'previous_login_at' => 'datetime',
            'last_activity_at' => 'datetime',
            'password' => 'hashed',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Use UUID for route model binding
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * Relationship with Roles
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relationship with Trading Accounts
     * Crucial for the withCount('accounts') in your controller
     */
    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class, 'user_id');
    }

    /**
     * Senior Dev Helper: Check if user is active
     * Usage in Blade: @if($user->is_active)
     */
    public function getIsActiveAttribute(): bool
    {
        return is_null($this->deleted_at);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
 * Relationship with Subscriptions
 */
public function subscriptions(): HasMany
{
    return $this->hasMany(UserSubscription::class, 'user_id');
}

/**
 * Get the current active subscription
 */
public function currentSubscription(): HasOne
{
    return $this->hasOne(UserSubscription::class, 'user_id')
        ->where('status', 'active')
        ->latestOfMany();
}

/**
 * Relationship with Support Tickets
 */
public function supportTickets(): HasMany
{
    return $this->hasMany(SupportTicket::class, 'user_id');
}

/**
 * Relationship with User Settings
 */
public function settings(): HasOne
{
    return $this->hasOne(UserSettings::class, 'user_id');
}

/**
 * Get formatted last login time
 * 
 * @return string Formatted date time or "Never" if user hasn't logged in
 */
public function getLastLoginDisplay(): string
{
    if (!$this->last_login_at) {
        return 'Never';
    }
    return $this->last_login_at->format('M d, Y - h:i A');
}

/**
 * Get formatted previous login time
 * 
 * @return string Formatted date time or "N/A" if no previous login
 */
public function getPreviousLoginDisplay(): string
{
    if (!$this->previous_login_at) {
        return 'N/A';
    }
    return $this->previous_login_at->format('M d, Y - h:i A');
}

/**
 * Get human-readable time usage
 * 
 * @return string Formatted time (e.g., "2h 30m" or "45m")
 */
public function getTotalTimeUsedDisplay(): string
{
    $minutes = $this->total_session_minutes ?? 0;
    
    if ($minutes < 60) {
        return $minutes . 'm';
    }
    
    $hours = intdiv($minutes, 60);
    $mins = $minutes % 60;
    
    if ($mins === 0) {
        return $hours . 'h';
    }
    
    return $hours . 'h ' . $mins . 'm';
}

/**
 * Get days since last login
 * 
 * @return int Number of days since last login
 */
public function getDaysSinceLastLogin(): int
{
    if (!$this->last_login_at) {
        return -1; // Never logged in
    }
    
    return now()->diffInDays($this->last_login_at);
}

/**
 * Record a login for this user
 */
public function recordLogin(): void
{
    // Move current login to previous
    if ($this->last_login_at) {
        $this->previous_login_at = $this->last_login_at;
    }
    
    // Set new login time
    $this->last_login_at = now();
    
    // Increment login count
    $this->total_login_count = ($this->total_login_count ?? 0) + 1;
    
    // Update last activity
    $this->last_activity_at = now();
    
    $this->save();
}

/**
 * Record session end (calculate time used)
 */
public function recordSessionEnd(): void
{
    if (!$this->last_login_at) {
        return;
    }
    
    // Calculate minutes since login
    $minutesUsed = now()->diffInMinutes($this->last_login_at);
    
    // Add to total session minutes
    $this->total_session_minutes = ($this->total_session_minutes ?? 0) + $minutesUsed;
    
    // Update last activity
    $this->last_activity_at = now();
    
    $this->save();
}
}
