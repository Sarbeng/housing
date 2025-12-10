<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Tenant extends Model
{
    //
    use HasFactory, Notifiable;
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    // defining relationship with RentalAgreement
    public function rentalAgreement()
    {
        return $this->hasMany(RentalAgreement::class);
    }

    // defining relationship with TenantFeeAllocation
    public function tenantFeeAllocations()
    {}

    // defining relationship with Unit
    public function unit(){
        return $this->belongsTo(Unit::class);
    }

    /**
     * Route notifications for the Arkesel channel.
     */
    public function routeNotificationForArkesel(): string
    {
        // Ensure the phone number is in the correct international format (e.g., 233241234567)
        return $this->phone_number;
    }


}
