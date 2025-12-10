<?php

namespace Database\Seeders;

use Database\Seeders\UserSeeder; // Make sure to import the class
use Database\Seeders\TenantUnitRentSeeder; // Import the TenantUnitRentSeeder
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            TenantUnitRentSeeder::class,
            //other seeders can be called here
        ]);
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
