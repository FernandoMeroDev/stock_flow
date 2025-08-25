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
        return view('livewire.warehouses.shelves.create');
    }

    public function store()
    {
        $this->form->store();
    }
}
