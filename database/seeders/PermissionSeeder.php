<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'products',
            'sales',
            'inventories',
            'warehouses',
            'cash-boxes',
            'providers',
            'purchases',
            'clients'
        ];
        foreach($permissions as $name){
            Permission::create(['name' => $name]);
        }
        $adminRole = Role::create(['name' => 'Administrador']);
        User::find(1)->assignRole($adminRole);
        $managerRole = Role::create(['name' => 'Manager']);
        $managerRole->givePermissionTo(['products', 'sales', 'cash-boxes', 'providers', 'purchases']);
        User::find(2)->assignRole($managerRole);
        User::find(3)->assignRole($managerRole);
        $sellerRole = Role::create(['name' => 'Vendedor']);
        $sellerRole->givePermissionTo(['sales']);
        foreach(User::where('name', 'LIKE', 'vendedor%')->get() as $seller){
            $seller->assignRole($sellerRole);
        }
    }
}
