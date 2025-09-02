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
    private int $inventory_product_count = 50;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products_max_id = Product::all()->count();
        $warehouses = Warehouse::all();
        
        $inventory = Inventory::create(['saved_at' => now()]);
        // // Use this if you want to test Inventory.Index with many registers
        // for($i = 0; $i < 19; $i++){
        //     $inventory = Inventory::create([
        //         'saved_at' => now()
        //             ->subSeconds(fake()->numberBetween(1, 60))
        //             ->subDays(fake()->numberBetween(1, 15))
        //     ]);
        // }
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
