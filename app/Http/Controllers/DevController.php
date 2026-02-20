<?php

namespace App\Http\Controllers;

use App\Models\Presentation;
use App\Models\Product;
use ErrorException;
use Illuminate\Http\Request;

class DevController extends Controller
{
    public function __invoke()
    {
        // $this->create_whiskys();
        // $this->create_beers();
    }

    public function create_beers()
    {
        $path = database_path('seeders/data/beers.csv');

        try {
            if(($handle = fopen($path, "r")) !== false){

                $headers = fgetcsv($handle);

                while (($row = fgetcsv($handle)) !== false) {
                    $data = array_combine($headers, $row);
                    $name = $data["\xEF\xBB\xBFProducto"];
                    $product = Product::create(['name' => $name, 'cash_box_id' => 2]);
                    $presentations_data = [
                        'UNIDAD' => 1,
                        'SIX PAC' => 6,
                        'LATAX6' => 6,
                        'LATAX12' => 12,
                        'CHANCLETAX6' => 6,
                        'CHANCLETAX12' => 12,
                        'CAJAX24' => 24,
                    ];
                    foreach($presentations_data as $name => $units){
                        $price = floatval($data[$name]);
                        if($price > 0){
                            Presentation::create([
                                'name' => $name,
                                'units' => intval($units),
                                'price' => $price,
                                'base' => $name == 'UNIDAD',
                                'product_id' => $product->id
                            ]);
                        }
                    }
                }

                fclose($handle);
            }
        } catch(ErrorException $error) {
            dump($error->getMessage());
        }
    }

    public function create_whiskys()
    {
        $path = database_path('seeders/data/whiskys.csv');

        try {
            if(($handle = fopen($path, "r")) !== false){

                $headers = fgetcsv($handle);

                while (($row = fgetcsv($handle)) !== false) {
                    $data = array_combine($headers, $row);
                    $name = $data["\xEF\xBB\xBFProducto"];
                    $product = Product::where('name', $name)->first();
                    $price = floatval($data['UNIDAD']);
                    if($price > 0)
                        $product->presentations->get(0)->update(['price' => $price]);
                    $presentations_data = [
                        'X2UNIDADES' => 2,
                        'CAJAX12' => 12,
                        'CAJAX24' => 24,
                        'PROMOX1' => 1,
                        'PROMOX2' => 2,
                        'PROMOX3' => 3,
                        'PROMOX4' => 4,
                        'PROMOX6' => 6,
                        'PROMOX12' => 12,
                    ];
                    foreach($presentations_data as $name => $units){
                        $price = floatval($data[$name]);
                        if($price > 0){
                            Presentation::create([
                                'name' => $name,
                                'units' => intval($units),
                                'price' => $name == 'X2UNIDADES' ? $price * intval($units) : $price,
                                'base' => false,
                                'product_id' => $product->id
                            ]);
                        }
                    }
                }

                fclose($handle);
            }
        } catch(ErrorException $error) {
            dump($error->getMessage());
        }
    }
}
