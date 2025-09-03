<?php

namespace App\Livewire\Forms\Inventories\Records;

use App\Models\InventoryProduct;
use App\Models\Product;
use App\Models\Warehouse;
use App\Rules\Inventories\Edit\Record\UniqueProduct;
use Livewire\Attributes\Locked;
use Livewire\Form;

class UpdateForm extends Form
{
    #[Locked]
    public InventoryProduct $inventory_product;

    public $product_id;

    public $name;

    public $price;

    public $incoming_count = 0;

    public $outgoing_count = 0;

    public $warehouse_existences = [];

    protected function rules()
    {
        $warehouses = Warehouse::all();
        $warehouses_ids = $warehouses->implode('id', ',');
        $warehouse_count = $warehouses->count();
        return [
            'product_id' => ['nullable', 'integer', 'min:1', new UniqueProduct(
                $this->inventory_product->inventory,
                ignore: $this->inventory_product->product_id
            )],
            'name' => 'required|string|min:1|max:500',
            'price' => 'nullable|decimal:0,3|min:0|max:9999.999',
            'incoming_count' => 'required|integer|min:0|max:9999',
            'outgoing_count' => 'required|integer|min:0|max:9999',
            'warehouse_existences' => "required|array:{$warehouses_ids}|size:{$warehouse_count}",
            'warehouse_existences.*' => 'required|integer|min:1|max:9999'
        ];
    }

    protected function validationAttributes() 
    {
        return [
            'product_id' => 'IDENTIFICADOR',
            'name' => 'Nombre',
            'price' => 'Precio',
            'incoming_count' => 'Entradas',
            'outgoing_count' => 'Salidas',
            'warehouse_existences' => 'Bodegas',
            'warehouse_existences.*' => 'Bodega #:position'
        ];
    }

    public function setInventoryRecord(InventoryProduct $inventory_product)
    {
        $this->inventory_product = $inventory_product;
        if($product = Product::find($inventory_product->product_id))
            $this->setProduct($product);
        else {
            $this->name = $inventory_product->name;
            $this->price = $inventory_product->price ?? 0;
        }
        $this->incoming_count = $inventory_product->incoming_count;
        $this->outgoing_count = $inventory_product->outgoing_count;
        foreach($inventory_product->stocks as $stock)
            $this->warehouse_existences[$stock->warehouse_id] = $stock->count;
        $this->resetValidation();
    }

    public function setProduct(Product $product)
    {
        $this->product_id = $product->id;
        $this->name = $product->name;
        $this->price = $product->price ?? 0;
    }

    public function update()
    {
        $this->validate();
        $this->inventory_product->update([
            'name' => $this->name,
            'price' => $this->price ?? 0,
            'incoming_count' => $this->incoming_count,
            'outgoing_count' => $this->outgoing_count,
            'product_id' => $this->product_id === '' ? null : $this->product_id,
        ]);
        foreach($this->warehouse_existences as $warehouse_id => $count){
            $this->inventory_product->stocks()->where(
                'warehouse_id', $warehouse_id
            )->first()->update(['count' => $count]);
        }
    }

    public function delete()
    {
        $this->inventory_product->delete();
    }
}
