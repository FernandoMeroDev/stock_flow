<?php

namespace App\Livewire\Warehouses\Shelves;

use App\Livewire\Forms\Warehouses\Shelves\UpdateForm;
use App\Models\Shelf;
use Livewire\Component;

class Edit extends Component
{
    public UpdateForm $form;

    public function mount(Shelf $shelf)
    {
        $this->form->setShelf($shelf);
    }

    public function render()
    {
        return view('livewire.warehouses.shelves.edit');
    }

    public function update()
    {
        $this->form->update();
        $this->redirect(route('levels.edit', $this->form->shelf->levels()->get()->get(1)->id));
    }

    public function delete()
    {
        $warehouse_id = $this->form->shelf->warehouse->id;
        $this->form->shelf->delete();
        $this->redirect(route('warehouses.edit', $warehouse_id));
    }
}
