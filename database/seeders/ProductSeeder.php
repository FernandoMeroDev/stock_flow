<?php

namespace Database\Seeders;

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

        if($this->seed_fake_data)
            Product::factory(20)->create();
        else
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
                    Product::create([
                        'name' => $data['nombre'],
                        'price' => $data['precio'] == '' ? null : $data['precio'],
                    ]);
                }

                fclose($handle);
            }
        } catch(ErrorException $error) {
            dump($error->getMessage());
        }
    }
}
