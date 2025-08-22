<?php

namespace App\Livewire\Warehouses;

use App\Livewire\Forms\Warehouses\UpdateForm;
use App\Models\Shelf;
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
            'shelves' => $this->query()
        ]);
    }

    private function query()
    {
        $shelves = Shelf::where('warehouse_id', $this->form->warehouse->id)
            ->orderBy('number')->paginate(15, pageName: 'shelves_page');

        if($shelves->isEmpty() && $shelves->currentPage() !== 1)
            $this->resetPage('shelves_page');

        return $shelves;
    }

    public function update()
    {
        $this->form->update();
    }
}
