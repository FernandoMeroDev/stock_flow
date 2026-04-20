<?php

namespace App\Http\Controllers\Inventories;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventories\StoreRequest;
use App\Models\Inventory;
use App\Models\InventoryProduct;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Warehouse;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        $validated = $request->validated();

        // Save Inventory record
        $inventory = Inventory::create(['saved_at' => $validated['saved_at']]);

        // Loop into products to create inventory_product records
        $products = Product::all();
        foreach($products as $product){
            $inventoryProduct = InventoryProduct::create([
                'name' => $product->name,
                'price' => $product->presentations->get(0)?->price ?? 0,
                'incoming_count' => 0,
                'outgoing_count' => 0,
                'product_id' => $product->id,
                'inventory_id' => $inventory->id
            ]);
            $warehouses = Warehouse::all();
            foreach($warehouses as $warehouse){
                Stock::create([
                    'count' => $product->warehouse_existences($warehouse)->sum('count'),
                    'warehouse_id' => $warehouse->id,
                    'inventory_product_id' => $inventoryProduct->id
                ]);
            }
        }

        return redirect()->route('warehouses.index');
    }
}
