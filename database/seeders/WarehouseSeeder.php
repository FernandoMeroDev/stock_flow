<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Shelf;
use App\Models\Shelves\Level;
use App\Models\Shelves\LevelProduct;
use App\Models\Warehouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    private int $products_per_level = 5;

    private int $products_count;

    private $warehouses = [
        [
            'name' => 'Depósito',
            'shelves' => [
                ['number' => 1, 'levels' => 5],
                ['number' => 2, 'levels' => 5],
                ['number' => 3, 'levels' => 4],
                ['number' => 4, 'levels' => 3],
                ['number' => 5, 'levels' => 4],
                ['number' => 6, 'levels' => 4],
                ['number' => 7, 'levels' => 5],
                ['number' => 8, 'levels' => 4],
                ['number' => 9, 'levels' => 4],
                ['number' => 10, 'levels' => 4],
                ['number' => 11, 'levels' => 4],
                ['number' => 12, 'levels' => 4],
                ['number' => 13, 'levels' => 1],
                ['number' => 14, 'levels' => 1],
                ['number' => 15, 'levels' => 1],
                ['number' => 16, 'levels' => 4],
            ]
        ],
        [
            'name' => 'Licorería',
            'shelves' => [
                ['number' => 1, 'levels' => 3],
                ['number' => 2, 'levels' => 4],
                ['number' => 3, 'levels' => 4],
                ['number' => 4, 'levels' => 4],
                ['number' => 5, 'levels' => 4],
                ['number' => 6, 'levels' => 4],
                ['number' => 7, 'levels' => 1],
                ['number' => 8, 'levels' => 1],
            ]
        ]
    ];

    public function __construct()
    {
        $this->products_count = Product::all()->count();
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach($this->warehouses as $warehouse){
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
}
