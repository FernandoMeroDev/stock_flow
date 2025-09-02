<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Specifies if there are images in the private storage
     */
    private $with_images = false;

    private array $products = [
        // Nombre, Codigo, Imagen
        ['Ron San Miguel Silver', null, '1'],
        ['Ron San Miguel Gold', null, '2'],
        ['Ron Pon Pon', null, '3'],
        ['Ron Caballo Viejo', null, '4'],
        ['Ron 100 Fuegos', null, '5'],
        ['Ron Viejo de Caldas', null, '6'],
        ['Ron Cubanero Oro', null, '7'],
        ['Ron Castillo Blanco', null, '8'],
        ['Ron Estelar', null, '9'],
        ['Ron Cartavio Silver', null, '10']
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::factory(20)->create();
        foreach($this->products as $product){
            Product::create([
                'name' => $product[0],
                'barcode' => $product[1],
                'img' => $this->with_images ? $product[2] . '.jpg' : null,
                'price' => null
            ]);
        }
    }
}
