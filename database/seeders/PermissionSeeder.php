<?php

namespace Database\Seeders;

use App\Http\Controllers\PermissionController;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public static function create()
    {
        $permissions = [
            'products',
            'sales',
            'inventories',
            'warehouses',
            'cash-boxes',
        ];
        foreach($permissions as $name){
            Permission::create(['name' => $name]);
        }
        $adminRole = Role::create(['name' => 'Administrador']);
        $sellerRole = Role::create(['name' => 'Vendedor']);
        $sellerRole->givePermissionTo(['products', 'sales']);
        User::find(1)->assignRole($adminRole);
        User::find(2)->assignRole($adminRole);
        foreach(User::where('name', 'LIKE', 'vendedor%')->get() as $seller){
            $seller->assignRole($sellerRole);
        }
    }

    /**
     * Run the database seeds.
     */
    public function __invoke(array $parameters = [])
    {
        static::create();
    }
}
