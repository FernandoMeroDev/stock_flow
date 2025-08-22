<?php

namespace Database\Seeders;

use App\Models\Shelf;
use App\Models\Warehouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    private $warehouses = [
        [
            'name' => 'Depósito',
            'shelves' => [
                ['number' => 1, 'levels' => 4],
                ['number' => 2, 'levels' => 4],
                ['number' => 3, 'levels' => 4],
                ['number' => 4, 'levels' => 4],
                ['number' => 5, 'levels' => 4],
            ]
        ],
        [
            'name' => 'Licorería',
            'shelves' => [
                ['number' => 1, 'levels' => 4],
                ['number' => 2, 'levels' => 4],
                ['number' => 3, 'levels' => 4],
                ['number' => 4, 'levels' => 4],
                ['number' => 5, 'levels' => 4],
            ]
        ]
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach($this->warehouses as $warehouse){
            $warehouse_created = Warehouse::create(['name' => $warehouse['name']]);
            $shelves = $warehouse['shelves'];
            foreach($shelves as $shelf){
                Shelf::create([
                    'number' => $shelf['number'],
                    'warehouse_id' => $warehouse_created->id
                ]);
            }
        }
    }
}
