<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    //
    protected $fillable = [
        'name','slug','currency','price','billing_interval','duration_days',
        'description','features','is_active','sort_order','profit_share','max_accounts'
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];
}
