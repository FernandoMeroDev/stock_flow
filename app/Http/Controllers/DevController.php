<?php

namespace App\Http\Controllers;

use App\Models\CashBox;
use App\Models\Presentation;
use App\Models\Product;
use ErrorException;

class DevController extends Controller
{
    private array $products = [
        ['AGUARDIENTE CRISTAL 750ML', 10.00],
        ['AGUARDIENTE CRISTAL 375ML', 5.25],
        ['AGUARDIENTE CRISTAL PEACH 700ML', 4.50],
        ['AGUARDIENTE CRISTAL SECO 750ML', 4.50],
        ['AGUARDIENTE TRÓPICO SECO 750ML', 9.00],
        ['AGUARDIENTE TRÓPICO SECO 375ML', 4.50],
        ['AGUARDIENTE ANTIOQUEÑO 1000ML', 17.00],
        ['AGUARDIENTE ANTIOQUEÑO 750ML', 15.00],
        ['AGUARDIENTE ANTIOQUEÑO ETIQUETA BLANCA 700ML', 9.00],
        ['AGUARDIENTE ANTIOQUEÑO ETIQUETA VERDE 700ML', 14.00],
        ['AGUARDIENTE NORTEÑO ESPECIAL 700ML', 7.50],
        ['AGUARDIENTE NORTEÑO ESPECIAL 375ML', 4.00],
        ['AGUARDIENTE NORTEÑO BLACK 700ML', 8.50],
        ['AGUARDIENTE NORTEÑO GOLD 700ML', 8.50],
        ['AGUARDIENTE CAÑA MANABITA ETIQUETA ROJA 375ML', 4.25],
        ['AGUARDIENTE CAÑA MANABITA ETIQUETA NEGRA 375ML', 4.00],
        ['AGUARDIENTE CAÑA MANABITA ETIQUETA ROJA 700ML', 8.50],
        ['AGUARDIENTE CAÑA MANABITA ETIQUETA ROJA TUBO 700ML', 12.00],
        ['AGUARDIENTE CAÑA MANABITA ETIQUETA NEGRA TUBO 700ML', 12.00],
        ['AGUARDIENTE CAÑA MANABITA ETIQUETA NEGRA 700ML', 7.50],
        ['AGUARDIENTE CAÑA MANABITA ROSE 700ML', 6.00],
        ['AGUARDIENTE CAÑA MANABITA VERDE 700ML', 6.00],
    ];

    public function __invoke()
    {
        foreach($this->products as $data){
            $name = $data[0]; $price = $data[1];
            $product = Product::create([
                'name' => $name,
                'cash_box_id' => CashBox::where('name', 'LIKE', '%Whiskys%')->first()->id
            ]);
            Presentation::create([
                'name' => 'UNIDAD',
                'units' => 1,
                'price' => $price,
                'base' => true,
                'product_id' => $product->id
            ]);
        }

        echo 'Operacion Ejecutada!';
    }
}
