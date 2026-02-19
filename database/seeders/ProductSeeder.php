<?php

namespace Database\Seeders;

use App\Models\Presentation;
use App\Models\Product;
use ErrorException;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    private bool $seed_fake_data = true;

    /**
     * Run the database seeds.
     */
    public function __invoke(array $parameters = [])
    {
        if(isset($parameters['seed_fake_data']))
            $this->seed_fake_data = $parameters['seed_fake_data'];

        if($this->seed_fake_data){
            for($i = 0; $i < 20; $i++){
                $product = Product::factory()->create();
                Presentation::create([
                    'name' => '1 Unidad',
                    'units' => 1,
                    'base' => true,
                    'price' => fake()->randomFloat(2, 0.01, 999.99),
                    'product_id' => $product->id
                ]);
            }
        } else
            $this->seedRealData();
    }

    private function seedRealData(): void
    {
        $path = database_path('seeders/data/products.csv');

        try {
            if(($handle = fopen($path, "r")) !== false){

                $headers = fgetcsv($handle);

                while (($row = fgetcsv($handle)) !== false) {
                    $data = array_combine($headers, $row);
                    $product = Product::create([
                        'name' => $data['nombre'],
                    ]);
                    Presentation::create([
                        'name' => '1 Unidad',
                        'units' => 1,
                        'base' => true,
                        'price' => $data['precio'],
                        'product_id' => $product->id
                    ]);
                }

                fclose($handle);
            }
        } catch(ErrorException $error) {
            dump($error->getMessage());
        }
    }
}
