<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Warehouse;
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
        $max_warehouses_id = Warehouse::all()->count();
        $datetime = now();
        for($i = 0; $i < $this->sales_count; $i++){
            $product = Product::find(
                fake()->numberBetween(1, $max_products_id)
            );
            $count = fake()->numberBetween(1, 10);
            $presentation = $product->presentations->get(0);
            $cash = ($count * $presentation->price) == 0
                ? 0.01
                : $count * $presentation->price;
            Sale::create([
                'name' => $presentation->complete_name(),
                'count' => $count,
                'cash' => $cash,
                'saved_at' => $datetime,
                'presentation_id' => $presentation->id,
                'warehouse_id' => fake()->numberBetween(1, $max_warehouses_id),
            ]);
            $datetime = $datetime->subMinutes(fake()->numberBetween(1, 60));
        }
    }
}
