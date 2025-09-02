<?php

namespace App\Livewire\Inventories\Edit;

use App\Models\Inventory;
use App\Models\InventoryProduct;
use App\Models\Warehouse;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;

class Main extends Component
{
    use WithPagination;

    #[Locked]
    public Inventory $inventory;

    public $search;

    public function mount(Inventory $inventory)
    {
        $this->inventory = $inventory;
    }

    public function render()
    {
        return view('livewire.inventories.edit.main', [
            'inventory_records' => $this->query(),
            'warehouses' => Warehouse::all()
        ]);
    }

    private function query()
    {
        $inventory_records = InventoryProduct::where(
            'inventory_id', $this->inventory->id
        )->where(
            'name', 'LIKE', "%{$this->search}%"
        )->orderBy('name')->paginate(15, pageName: 'inventory_records_page');

        if($inventory_records->isEmpty() && $inventory_records->currentPage() !== 1)
            $this->resetPage('inventory_records_page');
        return $inventory_records;
    }

    public function delete()
    {
        $this->inventory->delete();
        $this->redirect(route('inventories.index'));
    }
}
