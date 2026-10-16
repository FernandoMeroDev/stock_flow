<?php

namespace App\Rules\Products;

use App\Models\Presentation;
use App\Models\ProductWarehouse;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class HasStock implements ValidationRule, DataAwareRule
{
    protected array $data;

    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $products_found = collect([]);
        foreach($value as $movement){
            $presentation = Presentation::find($movement['presentation_id']);
            $product = $presentation->product;
            $key = $products_found->search(function (array $item, int $key) use($product) {
                return $item['product']->id == $product->id;
            });
            if(is_int($key)){
                $value = $products_found->get($key);
                $movement['count'] += $value['count'];
                $value['count'] = $movement['count'];
                $products_found->put($key, $value);
            } else {
                $products_found->add([
                    'product' => $product,
                    'count' => $movement['count'],
                ]);
            }
            $productWarehouse = ProductWarehouse::where('product_id', $product->id)
                ->where('warehouse_id', $this->data['warehouse_id'])
                ->first();
            if($productWarehouse){
                if(
                    ($presentation->units * $movement['count'])
                    > $productWarehouse->stock
                ) {
                    $fail(
                        'El stock del producto ' 
                        . $product->name 
                        . ' no es suficiente (Devolución: ' 
                        . $presentation->units * $movement['count'] 
                        . ' > Stock: '
                        . $productWarehouse->stock 
                        . ').'
                    );
                }
            } else {
                $fail('No hay stock del producto ' . $product->name);
            }
        }
    }
}
