<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    private $warehouses = [
        ['name' => 'Depósito'],
        ['name' => 'Licorería']
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach($this->warehouses as $warehouse)
            Warehouse::create([
                'name' => $warehouse['name']
            ]);
    }
}
