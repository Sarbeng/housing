<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantCharge extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'service_payment_id', // Optional link back to the expense log
        'service_type', // bin or cleaning
        'billing_month', // Which month is this charge for
        'amount_charged', // The fixed GHc 20 fee
        'is_paid',
    ];

    protected $casts = [
        'billing_month' => 'date',
        'is_paid' => 'boolean',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function servicePayment()
    {
        return $this->belongsTo(ServicePayment::class);
    }
}