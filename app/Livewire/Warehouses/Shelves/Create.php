<?php

namespace App\Livewire\Warehouses\Shelves;

use App\Livewire\Forms\Warehouses\Shelves\StoreForm;
use App\Models\Warehouse;
use Livewire\Component;

class Create extends Component
{
    public StoreForm $form;

    public function mount(Warehouse $warehouse)
    {
        $this->form->setWarehouse($warehouse);
    }

    public function render()
    {
        return view('livewire.warehouses.shelves.create', [
            'last_shelf_number' => $this->form->warehouse->shelves()->orderBy('number', 'desc')->first()->number ?? 0
        ]);
    }

    public function store()
    {
        $this->form->store();
    }
}
