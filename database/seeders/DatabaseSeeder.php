<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\ProductSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Truncate users table to delete all current users
        \App\Models\User::truncate();

        // Create the admin user
        \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'tonnyclothstore@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('tonny2812'),
        ]);

        $this->call(ProductSeeder::class);
    }
}
