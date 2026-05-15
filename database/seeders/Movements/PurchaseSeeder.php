<?php

namespace Database\Seeders\Movements;

use App\Models\Movements\Balance;
use App\Models\Movements\Movement;
use App\Models\Movements\Purchase;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $presentations = [];
        foreach(Product::limit(10)->get() as $products){
            $presentations[] = $products->presentations->first();
        }
        $n = 1;
        foreach($presentations as $presentation){
            $purchase = Purchase::create([
                'invoice_number' => '000-000-0000' . $n,
                'provider_id' => 1,
                'user_id' => 1
            ]);
            $movement = Movement::create([
                'count' => 5,
                'unitary_price' => $presentation->price,
                'movementable_id' => $purchase->id,
                'movementable_type' => $purchase::class,
                'presentation_id' => $presentation->id,
                'product_id' => $presentation->product->id
            ]);
            Balance::create([
                'units' => 5,
                'unitary_price' => $presentation->price,
                'movement_id' => $movement->id
            ]);
            $n++;
        }
    }
}
