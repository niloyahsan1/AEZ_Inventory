<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Test Product A',
            'buying_price' => 10.00,
            'selling_price' => 15.00,
            'quantity' => 100,
        ]);

        Product::factory()->count(50)->create(); // use factories
    }
}
