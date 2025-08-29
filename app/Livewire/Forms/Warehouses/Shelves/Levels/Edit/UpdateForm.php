<?php

namespace App\Livewire\Forms\Warehouses\Shelves\Levels\Edit;

use App\Models\Shelves\Level;
use App\Models\Shelves\LevelProduct;
use App\Rules\Warehouses\Shelves\Levels\Edit\ProductsExist;
use Livewire\Attributes\Locked;
use Livewire\Form;

class UpdateForm extends Form
{
    #[Locked]
    public Level $level;

    #[Locked]
    public $products = [];

    protected function rules()
    {
        return [
            'products' => ['array', 'min:0', 'max:9999', new ProductsExist],
            'products.*' => 'required|array:name,count|size:2',
            'products.*.count' => 'required|integer|min:1|max:9999'
        ];
    }
 
    protected function validationAttributes() 
    {
        return [
            'products' => 'Productos',
            'products.*' => 'Producto #:position',
            'products.*.count' => 'Cantidad del Producto',
        ];
    }

    public function setProducts(Level $level): void
    {
        $this->level = $level;
        foreach($level->products()->get() as $product){
            $this->products[$product->id] = [
                'name' => $product->name,
                'count' => $product->pivot->count
            ];
        }
    }

    public function update(): void
    {
        $this->validate();
        foreach($this->level->products as $product)
            $product->pivot->delete();
        foreach($this->products as $id => $product_data)
            LevelProduct::create([
                'count' => $product_data['count'],
                'level_id' => $this->level->id, 
                'product_id' => $id
            ]);
    }

    public function empty(): void
    {
        $this->reset(['products']);
    }
}
