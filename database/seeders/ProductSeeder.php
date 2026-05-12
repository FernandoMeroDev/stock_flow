<?php

namespace Database\Seeders;

use App\Models\CashBox;
use App\Models\Presentation;
use App\Models\Product;
use ErrorException;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cash_boxes = [
            CashBox::create(['name' => 'Caja 1']),
            CashBox::create(['name' => 'Caja 2']),
            CashBox::create(['name' => 'Caja 3'])
        ];

        $path = database_path('seeders/data/products.csv');

        try {
            if(($handle = fopen($path, "r")) !== false){

                $headers = fgetcsv($handle);

                while (($row = fgetcsv($handle)) !== false) {
                    $data = array_combine($headers, $row);
                    $product = Product::create([
                        'name' => $data['nombre'],
                        'cash_box_id' => $cash_boxes[
                            fake()->numberBetween(0, count($cash_boxes) - 1)
                        ]->id,
                        'created_by' => 1
                    ]);
                    Presentation::create([
                        'name' => '1 UNIDAD',
                        'units' => 1,
                        'base' => true,
                        'price' => $data['precio'],
                        'product_id' => $product->id,
                    ]);
                }

                fclose($handle);
            }
        } catch(ErrorException $error) {
            dump($error->getMessage());
        }
    }
}
