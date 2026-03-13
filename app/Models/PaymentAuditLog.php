<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentAuditLog extends Model
{
    use HasFactory;

    protected $table = 'payment_audit_logs';

    protected $fillable = [
        'user_id',
        'payment_transaction_id',
        'action',
        'old_status',
        'new_status',
        'confirmed_by_user_id',
        'confirmed_by_admin_id',
        'reason',
        'ip_address',
        'user_agent',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentTransaction()
    {
        return $this->belongsTo(PaymentTransaction::class);
    }

    public function confirmedByUser()
    {
        return $this->belongsTo(User::class, 'confirmed_by_user_id');
    }

    public function confirmedByAdmin()
    {
        return $this->belongsTo(User::class, 'confirmed_by_admin_id');
    }
}
