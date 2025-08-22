<?php

namespace App\Livewire\Warehouses;

use App\Livewire\Forms\Warehouses\UpdateForm;
use App\Models\Warehouse;
use Livewire\Component;

class Edit extends Component
{
    public UpdateForm $form;

    public function mount(Warehouse $warehouse)
    {
        $this->form->setWarehouse($warehouse);
    }

    public function render()
    {
        return view('livewire.warehouses.edit', [
            'shelves' => []
        ]);
    }

    public function update()
    {
        $this->form->update();
    }
}
