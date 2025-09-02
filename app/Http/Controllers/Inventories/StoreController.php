<?php

namespace App\Http\Controllers\Inventories;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventories\StoreRequest;
use App\Models\Inventory;
use App\Models\InventoryProduct;
use App\Models\Movement;
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
            // Seek product's movement records
            $movements = $this->findMovements($product);
            $inventoryProduct = InventoryProduct::create([
                'name' => $product->name,
                'price' => $product->price ?? 0,
                'incoming_count' => $movements['incoming_count'],
                'outgoing_count' => $movements['outgoing_count'],
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

        if(isset($validated['empty_movements'])){
            $movements = Movement::all();
            foreach($movements as $movement)
                $movement->delete();
        }

        return redirect()->route('warehouses.index');
    }

    private function findMovements(Product $product): array
    {
        $result = [
            'incoming_count' => 0,
            'outgoing_count' => 0,
        ];
        $movements = Movement::where('product_id', $product->id)->get();
        foreach($movements as $movement){
            if($movement->type == 'i')
                $result['incoming_count'] += $movement->count;
            else if($movement->type == 'o')
                $result['outgoing_count'] += $movement->count;
        }
        return $result;
    }
}
