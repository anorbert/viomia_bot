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
        'meta',      // Added to fillable
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'meta' => 'array',
        'active' => 'boolean',
        'connected' => 'boolean',
        'deleted_at' => 'datetime',
    ];

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
            return Crypt::decrypt($value);
        } catch (\Exception $e) {
            return "Error Decrypting"; 
        }
    }

    /**
     * Encrypt password mutator
     * usage: $account->password = 'secret'
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Crypt::encrypt($value);
    }
}