<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Shelf;
use App\Models\Shelves\Level;
use App\Models\Shelves\LevelProduct;
use App\Models\Warehouse;
use ErrorException;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    private $warehouses_count = 2;

    private int $products_per_level = 5;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $max_product_id = Product::all()->count();
        $warehouses = Warehouse::factory($this->warehouses_count)->create();
        foreach($warehouses as $warehouse){
            for($i = 0; $i < 5; $i++){
                $shelf = Shelf::create([
                    'number' => $i + 1,
                    'warehouse_id' => $warehouse->id
                ]);
                for($j = 0; $j < 5; $j++){
                    $level = Level::create([
                        'number' => $j,
                        'shelf_id' => $shelf->id
                    ]);
                    for($k = 0; $k < $this->products_per_level; $k++)
                        LevelProduct::create([
                            'count' => fake()->numberBetween(1, 5),
                            'level_id' => $level->id,
                            'product_id' => fake()->numberBetween(1, $max_product_id)
                        ]);
                }
            }
        }
    }

    # Deprecated
    // private function seedRealData(): void
    // {
    //     $path = database_path('seeders/data/warehouses.csv');

    //     try {
    //         if(($handle = fopen($path, "r")) !== false){

    //             $headers = fgetcsv($handle);

    //             $warehouse = null;

    //             while (($row = fgetcsv($handle)) !== false) {
    //                 $data = array_combine($headers, $row);
                    
    //                 if(is_null($warehouse) || $warehouse->name != $data['nombre_bodega']){
    //                     $warehouse = Warehouse::create(['name' => $data['nombre_bodega']]);
    //                 }

    //                 $shelf = Shelf::create([
    //                     'number' => $data['numero_percha'],
    //                     'warehouse_id' => $warehouse->id
    //                 ]);
    //                 for($i = 0; $i <= $data['numero_pisos']; $i++){
    //                     Level::create([
    //                         'number' => $i,
    //                         'shelf_id' => $shelf->id
    //                     ]);
    //                 }
    //             }

    //             fclose($handle);
    //         }
    //     } catch(ErrorException $error) {
    //         dump($error->getMessage());
    //     }
    // }
}
