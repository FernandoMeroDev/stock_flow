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
    private bool $seed_fake_data = true;

    private $warehouses_count = 2;

    private int $products_per_level = 5;

    private int $products_count;

    public function __construct()
    {
        $this->products_count = Product::all()->count();
    }

    /**
     * Run the database seeds.
     */
    public function __invoke(array $parameters = [])
    {
        if(isset($parameters['seed_fake_data']))
            $this->seed_fake_data = $parameters['seed_fake_data'];

        if($this->seed_fake_data){
            $this->seedFakeData();
        } else {
            $this->seedRealData();
        }
    }

    private function seedFakeData(): void
    {
        $warehouses = Warehouse::factory($this->warehouses_count)->create();
        foreach($warehouses as $warehouse){
            $warehouse_created = Warehouse::create(['name' => $warehouse['name']]);
            $shelves = $warehouse['shelves'];
            foreach($shelves as $shelf){
                $shelf_created = Shelf::create([
                    'number' => $shelf['number'],
                    'warehouse_id' => $warehouse_created->id
                ]);
                for($i = 0; $i <= $shelf['levels']; $i++){
                    $level = Level::create([
                        'number' => $i,
                        'shelf_id' => $shelf_created->id
                    ]);
                    if($this->products_count < 1) continue;
                    for($j = 0; $j < $this->products_per_level; $j++)
                        LevelProduct::create([
                            'count' => fake()->numberBetween(1, 12),
                            'level_id' => $level->id,
                            'product_id' => fake()->numberBetween(1, $this->products_count)
                        ]);
                }
            }
        }
    }

    private function seedRealData(): void
    {
        $path = database_path('seeders/data/warehouses.csv');

        try {
            if(($handle = fopen($path, "r")) !== false){

                $headers = fgetcsv($handle);

                $warehouse = null;

                while (($row = fgetcsv($handle)) !== false) {
                    $data = array_combine($headers, $row);
                    
                    if(is_null($warehouse) || $warehouse->name != $data['nombre_bodega']){
                        $warehouse = Warehouse::create(['name' => $data['nombre_bodega']]);
                    }

                    $shelf = Shelf::create([
                        'number' => $data['numero_percha'],
                        'warehouse_id' => $warehouse->id
                    ]);
                    for($i = 0; $i <= $data['numero_pisos']; $i++){
                        Level::create([
                            'number' => $i,
                            'shelf_id' => $shelf->id
                        ]);
                    }
                }

                fclose($handle);
            }
        } catch(ErrorException $error) {
            dump($error->getMessage());
        }
    }
}
