<?php

namespace App\Livewire\Movements\WarehouseChanges;

use App\Livewire\Forms\Movements\WarehouseChanges\CreateForm;
use App\Models\Presentation;
use App\Models\Product;
use App\Models\Warehouse;
use Flux\Flux;
use Livewire\Component;

class Create extends Component
{
    public CreateForm $form;

    public function render()
    {
        return view('livewire.movements.warehouse-changes.create', [
            'warehouses' => Warehouse::all(),
            'warehouses_to' => Warehouse::where('id', '!=', $this->form->warehouse_id)->get()
        ]);
    }

    public function addPresentation(mixed $primary_key, mixed $type)
    {
        $product = null;
        switch($type){
            case 'id':
                $presentation = Presentation::find($primary_key); break;
            case 'barcode':
                $product = Product::where('barcode', $primary_key)->first();
                $presentation = $product?->presentations->get(0); break;
        }
        if($presentation){
            if(
                $presentation->product
                    ->stock_in_warehouse(
                        Warehouse::find($this->form->warehouse_id)
                    ) 
                < $presentation->units
            )
                abort(403);
            $this->form->addProduct($presentation);
            $this->dispatch('sale-created');
        } else {
            if($type === 'barcode')
                $this->addError('barcode', 'El Código del producto no esta registrado.');
        }
    }

    public function removePresentation(int $key)
    {
        unset($this->form->movements[$key]);
    }

    public function store()
    {
        $this->form->store();
        Flux::toast('Los productos fueron cambiados!', variant: 'success');
        $this->redirect(route('warehouse-changes.create'));
    }
}
