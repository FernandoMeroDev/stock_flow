<?php

namespace App\Livewire\Inventories\Edit;

use App\Livewire\Forms\Inventories\UpdateForm;
use App\Models\Inventory;
use Livewire\Component;

class Update extends Component
{
    public UpdateForm $form;

    public function mount(Inventory $inventory)
    {
        $this->form->setInventory($inventory);
    }

    public function render()
    {
        return view('livewire.inventories.edit.update');
    }

    public function update()
    {
        $this->form->update();
    }
}
