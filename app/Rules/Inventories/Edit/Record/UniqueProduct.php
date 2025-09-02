<?php

namespace App\Rules\Inventories\Edit\Record;

use App\Models\Inventory;
use App\Models\InventoryProduct;
use App\Models\Product;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueProduct implements ValidationRule
{
    private Inventory $inventory;

    public function __construct(Inventory $inventory)
    {
        $this->inventory = $inventory;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if($product = Product::find($value)){
            $inventory_product = InventoryProduct::where(
                'inventory_id', $this->inventory->id
            )->where(
                'product_id', $product->id
            )->first();
            if($inventory_product)
                $fail('El producto ya fue registrado.');
        }
    }
}
