<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\InventoryProduct;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Warehouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    private bool $seed_fake_data = true;

    /**
     * Create many inventories registers with no data
     */
    private bool $empty_inventories = true;

    private int $inventory_product_count = 50;

    /**
     * Run the database seeds.
     */
    public function __invoke(array $parameters = [])
    {
        if(isset($parameters['seed_fake_data']))
            $this->seed_fake_data = $parameters['seed_fake_data'];

        if( ! $this->seed_fake_data )
            return;

        $products_max_id = Product::all()->count();
        $warehouses = Warehouse::all();
        
        $datetime = now();
        $inventory = Inventory::create(['saved_at' => $datetime]);
        if($this->empty_inventories){
            for($i = 0; $i < 29; $i++){
                $datetime = $datetime->subSeconds(fake()->numberBetween(1, 60))
                    ->subDays(fake()->numberBetween(1, 15));
                Inventory::create([
                    'saved_at' => $datetime
                ]);
            }
        }
        for($i = 0; $i < $this->inventory_product_count; $i++){
            $product = Product::find(
                fake()->numberBetween(1, $products_max_id)
            );
            $inventory_product = InventoryProduct::create([
                'name' => $product->name,
                'price' => $product->price ?? 0,
                'incoming_count' => 10,
                'outgoing_count' => 10,
                'product_id' => $product->id,
                'inventory_id' => $inventory->id
            ]);
            foreach($warehouses as $warehouse){
                Stock::create([
                    'count' => 10,
                    'warehouse_id' => $warehouse->id,
                    'inventory_product_id' => $inventory_product->id
                ]);
            }
        }
    }
}
