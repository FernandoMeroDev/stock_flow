<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Specifies if create fake data in the Data Base for testing
     */
    private $create_fake_data = true;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        if($this->create_fake_data)
            $this->seedFakeData();
        else
            $this->seedRealData();
    }

    private function seedFakeData(): void
    {
        $this->call([
            ProductSeeder::class,
            WarehouseSeeder::class,
            MovementSeeder::class,
            InventorySeeder::class
        ]);
    }

    private function seedRealData(): void
    {
        $this->call([
            WarehouseSeeder::class
        ]);
    }
}
