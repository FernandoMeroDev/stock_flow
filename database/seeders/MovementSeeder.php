<?php

namespace Database\Seeders;

use App\Models\Movement;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MovementSeeder extends Seeder
{
    private bool $seed_fake_data = true;

    /**
     * Count of Movements to create
     */
    private int $count = 20;

    /**
     * Run the database seeds.
     */
    public function __invoke(array $parameters = [])
    {
        if(isset($parameters['seed_fake_data']))
            $this->seed_fake_data = $parameters['seed_fake_data'];

        if( ! $this->seed_fake_data )
            return;

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
