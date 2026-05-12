<?php

namespace Database\Seeders;

use App\Models\Provider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i = 0; $i < 5; $i++)
            Provider::create([
                'name' => fake()->name(),
                'user_id' => 1
            ]);
        $managers = Role::findByName('Manager')->users;
        foreach($managers as $manager)
            for($i = 0; $i < 5; $i++)
                Provider::create([
                    'name' => fake()->name(),
                    'user_id' => $manager->id
                ]);
    }
}
