<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductWarehouse;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class DeployOperation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:deploy-operation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Versatile command. Execute Deploy operations needed by current deploy context.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $products = Product::where('total_stock', '>', 0)->get();
        foreach($products as $product){
            ProductWarehouse::create([
                'stock' => $product->total_stock,
                'warehouse_id' => 1,
                'product_id' => $product->id
            ]);
        }

        // [OLD]
        // $products = Product::all();
        // foreach($products as $product){
        //     if(Str::contains($product->name, 'jhonnie', true)){
        //         $product->name = str_replace('JHONNIE', 'JOHNNIE', $product->name);
        //         $product->save();
        //     }
        // }
    }
}
