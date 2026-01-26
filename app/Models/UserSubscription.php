<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    //
    protected $fillable = [
        'user_id','subscription_plan_id','status',
        'starts_at','ends_at','auto_renew',
        'reference','amount'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
        'auto_renew'=> 'boolean',
        'amount'    => 'decimal:2',
    ];

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
