<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            // Use Hash::make() to properly encrypt the password
            'password' => Hash::make('password'), 
            // Add any other required fields, like 'email_verified_at'
            'email_verified_at' => now(), 
        ]);

        // You can add more users here
        User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('secret'),
            'email_verified_at' => now(), 
        ]);
    }
}
