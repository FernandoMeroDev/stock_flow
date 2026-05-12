<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            'manager1',
            'manager2',
            'vendedor1',
            'vendedor2',
            'vendedor3',
        ];

        foreach($users as $name){
            User::create([
                'name' => $name,
                'email' => $name . '@stockflow.test',
                'password' => Hash::make('password')
            ]);
        }
    }
}
