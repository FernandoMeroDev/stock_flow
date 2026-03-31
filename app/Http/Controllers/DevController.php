<?php

namespace App\Http\Controllers;

use App\Models\CashBox;
use App\Models\Presentation;
use App\Models\Product;
use App\Models\User;
use ErrorException;
use Illuminate\Support\Facades\Hash;

class DevController extends Controller
{
    public function __invoke()
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

        echo 'Operacion Ejecutada!';
    }
}
