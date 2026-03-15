<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes; // Added for trash management
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Crypt;

class Account extends Model
{
    use HasFactory, SoftDeletes; // Added SoftDeletes trait

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'platform',
        'server',
        'login',
        'password',
        'account_type',
        'active',
        'connected', // Added to fillable based on your previous request
        'meta', 
        'is_verified',
        'verified_at',
        'verification_notes',
        'rejection_reason',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'meta' => 'array',
        'active' => 'boolean',
        'connected' => 'boolean',
        'deleted_at' => 'datetime',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    /**
     * Relationship: Account has many TradeLog records
     */
    public function tradeLogs()
    {
        return $this->hasMany(TradeLog::class);
    }

    /**
     * Relationship: Account belongs to a User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship: Account has one Snapshot
     */
    public function snapshots(): HasOne
    {
        return $this->hasOne(AccountSnapshot::class);
    }

    /**
     * Decrypt password accessor
     * usage: $account->password
     */
    public function getPasswordAttribute($value)
    {
        if (empty($value)) return null;
        
        try {
            // Check if the value is already decrypted or needs decryption
            if (strpos($value, 'eyJ') === 0) { // Check if it looks like Laravel encrypted string
                return Crypt::decrypt($value);
            }
            return $value;
        } catch (\Exception $e) {
            // Return a placeholder if decryption fails
            return null;
        }
    }

    /**
     * Encrypt password mutator
     * usage: $account->password = 'secret'
     */
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = Crypt::encrypt($value);
        }
    }
}