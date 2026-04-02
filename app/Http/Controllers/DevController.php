<?php

namespace App\Http\Controllers;

use Database\Seeders\PermissionSeeder;

class DevController extends Controller
{
    public function __invoke()
    {
        PermissionSeeder::create();
        echo 'Operacion Ejecutada!';
    }
}
