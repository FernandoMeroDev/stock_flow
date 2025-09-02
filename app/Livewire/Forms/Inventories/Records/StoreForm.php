<?php

namespace App\Livewire\Forms\Inventories\Records;

use App\Models\Inventory;
use App\Models\InventoryProduct;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Warehouse;
use App\Rules\Inventories\Edit\Record\UniqueProduct;
use Livewire\Attributes\Locked;
use Livewire\Form;

class StoreForm extends Form
{
    #[Locked]
    public Inventory $inventory;

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
            'product_id' => ['nullable', 'int', 'min:1', new UniqueProduct($this->inventory)],
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

    public function setInventory(Inventory $inventory)
    {
        $this->inventory = $inventory;
    }

    public function setWarehouseExistences()
    {
        foreach(Warehouse::all() as $warehouse){
            $this->warehouse_existences[$warehouse->id] = 0;
        }
    }

    public function setProduct(Product $product)
    {
        $this->product_id = $product->id;
        $this->name = $product->name;
        $this->price = $product->price ?? 0;
    }

    public function store()
    {
        $this->validate();
        $inventory_product = InventoryProduct::create([
            'name' => $this->name,
            'price' => $this->price ?? 0,
            'incoming_count' => $this->incoming_count,
            'outgoing_count' => $this->outgoing_count,
            'product_id' => $this->product_id,
            'inventory_id' => $this->inventory->id
        ]);
        foreach($this->warehouse_existences as $warehouse_id => $count){
            Stock::create([
                'count' => $count,
                'warehouse_id' => $warehouse_id,
                'inventory_product_id' => $inventory_product->id
            ]);
            $this->warehouse_existences[$warehouse_id] = 0;
        }
        $this->resetExcept(['inventory', 'warehouse_existences']);
    }
}
