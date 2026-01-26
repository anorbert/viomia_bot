<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    //
    protected $fillable = [
        'user_id','subscription_plan_id','reference','provider',
        'currency','amount','status','provider_txn_id','checkout_url',
        'payload','paid_at'
    ];

    protected $casts = [
        'payload' => 'array',
        'amount'  => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function user() 
    { 
        return $this->belongsTo(User::class); 
    }
    public function plan() 
    { 
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id'); 
    }
}
