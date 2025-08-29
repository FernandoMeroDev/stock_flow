<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PurchaseSeeder extends Seeder
{
    /**
     * Count of Purchases to create
     */
    private int $count = 10;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $max_products_id = Product::all()->count();
        for($i = 0; $i < $this->count; $i++){
            Purchase::create([
                'count' => fake()->numberBetween(1, 15),
                'product_id' => fake()->numberBetween(1, $max_products_id)
            ]);
        }
    }
}
