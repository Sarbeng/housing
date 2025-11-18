<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class ServicePayment extends Model
{
    // this enables you to use laravels factory system to generate fake data.
     use HasFactory;
     // this sets only the fields that cannot be mass assigned
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

     protected $casts = [
        'date_paid' => 'date',
    ];


}
