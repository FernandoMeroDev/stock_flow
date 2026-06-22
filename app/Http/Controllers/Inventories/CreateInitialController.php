<?php

namespace App\Http\Controllers\Inventories;

use App\Http\Controllers\Controller;
use App\Models\Movements\Balance;
use App\Models\Movements\Movement;
use App\Models\Movements\Purchase;
use App\Models\Product;
use App\Models\ProductWarehouse;
use App\Models\Provider;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateInitialController extends Controller
{
    public function __invoke()
    {
        DB::table('movements')->delete();
        DB::table('balances')->delete();
        DB::table('purchases')->delete();
        DB::table('disposals')->delete();
        DB::table('purchase_devolutions')->delete();
        DB::table('disposal_devolutions')->delete();
        DB::table('warehouse_changes')->delete();
        // Loop into products to create inventory_product records
        $products = Product::all();
        foreach($products as $product){
            $warehouses = Warehouse::all();
            foreach($warehouses as $warehouse){
                $count = $product->warehouse_existences($warehouse)->sum('count');
                if($count > 0){
                    // Checkear si el producto tiene registros en el kardex de esa bodega
                    $movements = Movement::where('product_id', $product->id)
                        ->where('warehouse_id', $warehouse->id)
                        ->orderBy('created_at', 'desc')
                        ->orderBy('id', 'desc')
                        ->get();
                    if($movements->isEmpty()){
                        $this->registerInitialInventory($product, $warehouse, $count);
                        dump('Inventario Inicial Registrado!');
                        dump([
                            'producto' => $product->name,
                            'bodega' => $warehouse->name,
                            'cantidad' => $count
                        ]);
                    }
                }
            }
        }
        return 'Ejecutado!';
    }

    private function registerInitialInventory(
        Product $product, Warehouse $warehouse, int $count
    ): void
    {
        $purchase = Purchase::create([
            'invoice_number' => '000-000-000000',
            'provider_id' => $this->queryOrCreateProvider()->id,
            'user_id' => Auth::user()->id,
        ]);
        $presentation = $product->presentations()->withTrashed()->where('base', true)->first();
        $movement = Movement::create([
            'count' => $count,
            'unitary_price' => $presentation->price,
            'movementable_id' => $purchase->id,
            'movementable_type' => Purchase::class,
            'presentation_id' => $presentation->id,
            'product_id' => $presentation->product->id,
            'warehouse_id' => $warehouse->id
        ]);
        Balance::create([
            'units' => $count,
            'unitary_price' => $movement['unitary_price'],
            'movement_id' => $movement->id
        ]);
        ProductWarehouse::create([
            'stock' => $count,
            'product_id' => $presentation->product->id,
            'warehouse_id' => $warehouse->id
        ]);
    }

    private function queryOrCreateProvider(): Provider
    {
        $provider = Provider::where('name', 'Inventario Inicial')
            ->where('user_id', Auth::user()->id)->first();
        if($provider)
            return $provider;
        else
            return Provider::create([
                'name' => 'Inventario Inicial',
                'user_id' => Auth::user()->id
            ]);
    }
}
