<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Tenant;
use App\Models\RentalAgreement;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class TenantUnitRentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // creating a collection of units to be seeded
        $units = [
            ['Apartment A','900'],
            ['Apartment B','900'],
            ['Apartment C','900'],
            ['Apartment E','900'],
        ];

        // Loop through each unit and create a record in the database
        foreach ($units as $unit) {
            Unit::create([
                'name' => $unit[0],
                'monthly_rent' => $unit[1],
            ]);

        }

        // You can add more tenants here
         Tenant::create([
            'name' => 'Dr Adom',
            // Use Hash::make() to properly encrypt the password
            'phone_number' => '+233547127696',
            // Add any other required fields, like 'email_verified_at'
        ]);
         Tenant::create([
            'name' => 'Mr Koomson',
            // Use Hash::make() to properly encrypt the password
            'phone_number' => '+233504035272',
            // Add any other required fields, like 'email_verified_at'
        ]);
         Tenant::create([
            'name' => 'Mr Marcus Arhinful',
            // Use Hash::make() to properly encrypt the password
            'phone_number' => '+233546920023',
            // Add any other required fields, like 'email_verified_at'
        ]);
         Tenant::create([
            'name' => 'Dr Haruna',
            // Use Hash::make() to properly encrypt the password
            'phone_number' => '+233503795076',
            // Add any other required fields, like 'email_verified_at'
        ]);

    //  Create the Rental Agreements using the Tenant IDs and Unit IDs

    RentalAgreement::create([
        'tenant_id' => 1, // Assuming Dr Adom has ID 1
        'unit_id' => 2, // Apartment B
        'monthly_rent_amount' => 900,
        'start_date' => '2025-01-31',
        'end_date' => '2026-01-31',
    ]);
    RentalAgreement::create([
        'tenant_id' => 2, // Assuming Mr Koomson has ID 2
        'unit_id' => 1, // Apartment A
        'monthly_rent_amount' => 900,
        'start_date' => '2024-12-31', // YYYY-MM-DD
        'end_date' => '2025-12-31',

    ]);
    RentalAgreement::create([
        'tenant_id' => 3, // Assuming Mr Marcus has ID 3
        'unit_id' => 3, // Apartment C
        'monthly_rent_amount' => 900,
        'start_date' => '2024-10-31',
        'end_date' => '2025-10-31',

    ]);
    RentalAgreement::create([
        'tenant_id' => 4, // Assuming Dr Haruna has ID 4
        'unit_id' => 4, // Apartment E
        'monthly_rent_amount' => 900,
        'start_date' => '2024-12-31',
        'end_date' => '2025-12-31',
    ]);
    }



}
