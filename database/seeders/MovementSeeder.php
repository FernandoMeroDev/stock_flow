<?php

namespace Database\Seeders;

use App\Models\Movement;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MovementSeeder extends Seeder
{
    /**
     * Count of Movements to create
     */
    private int $count = 20;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $max_products_id = Product::all()->count();
        for($i = 0; $i < $this->count; $i++){
            Movement::create([
                'type' => fake()->randomElement(['i', 'o']),
                'count' => fake()->numberBetween(1, 15),
                'product_id' => fake()->numberBetween(1, $max_products_id)
            ]);
        }
    }
}
