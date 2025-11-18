<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RentalAgreement extends Model
{
    //

    // this sets only the fields that cannot be mass assigned
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    // casts for date fields
     protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Relationship to the Tenant
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Relationship to the Unit (Property)
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }




}
