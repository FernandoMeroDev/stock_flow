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
            'patricia',
            'vendedor1',
            'vendedor2',
            'vendedor3',
            'vendedor4',
            'vendedor5',
        ];

        foreach($users as $name){
            User::create([
                'name' => $name,
                'email' => $name . '@ellicenciado.app',
                'password' => Hash::make('licenciado')
            ]);
        }
    }
}
