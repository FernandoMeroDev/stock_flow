<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Specifies if create fake data in the Data Base for testing
     */
    private $create_fake_data = false;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Administrador',
            'email' => config('app.admin.email'),
            'password' => Hash::make(config('app.admin.password'))
        ]);

        $this->call([
            ProductSeeder::class,
            WarehouseSeeder::class,
            MovementSeeder::class,
            InventorySeeder::class,
            SaleSeeder::class,
        ], parameters: ['seed_fake_data' => $this->create_fake_data]);
    }
}
