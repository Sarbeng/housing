<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    //
    use HasFactory;
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
}
