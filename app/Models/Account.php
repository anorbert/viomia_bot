<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Crypt;

class Account extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'user_id',
        'platform',
        'server',
        'login',
        'password',
        'account_type',
        'active',
        'is_verified',
        'verified_at',
        'verification_notes',
        'rejection_reason',
    ];

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    protected $casts = [
        'meta' => 'array',
        'active' => 'boolean',
        'connected' => 'boolean',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    // Decrypt password accessor
    public function getPasswordAttribute($value)
    {
        try {
            return Crypt::decrypt($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    // Encrypt password mutator
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Crypt::encrypt($value);
    }

    public function snapshots()
    {
        return $this->hasOne(AccountSnapshot::class);
    }
    
}
