<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SaleSeeder extends Seeder
{
    private bool $seed_fake_data = true;

    private int $sales_count = 30;

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
        $datetime = now();
        for($i = 0; $i < $this->sales_count; $i++){
            $product = Product::find(
                fake()->numberBetween(1, $max_products_id)
            );
            $count = fake()->numberBetween(1, 10);
            Sale::create([
                'name' => $product->name,
                'count' => $count,
                'cash' => $count * $product->price,
                'saved_at' => $datetime,
                'product_id' => $product->id,
            ]);
            $datetime = $datetime->subSeconds(fake()->numberBetween(1, 60))
                ->subDays(fake()->numberBetween(1, 15));
        }
    }
}
