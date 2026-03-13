<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes; 
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasUuids, SoftDeletes; 

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'country_code',
        'phone_number',
        'role_id',
        'otp',
        'zone_id',
        'profile_photo',
        'is_active',
        'is_default_pin',
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
            'password' => 'hashed',
            'deleted_at' => 'datetime', // Ensure this is cast to a date
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
}