<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    //
    use HasFactory;


    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'monthly_rent' => 'decimal:2',
    ];

    public function getYearlyRentAttribute()
    {
        return $this->monthly_rent * 12;
    }

     public function rentalAgreements()
    {
        return $this->hasMany(RentalAgreement::class);
    }
}
