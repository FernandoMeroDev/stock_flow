<?php

namespace App\Livewire\Warehouses;

use App\Models\Warehouse;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search;

    public function render()
    {
        return view('livewire.warehouses.index', [
            'warehouses' => $this->query()
        ]);
    }

    private function query()
    {
        $warehouses = Warehouse::orderBy('name')->paginate(15, pageName: 'warehouses_page');

        if($warehouses->isEmpty() && $warehouses->currentPage() !== 1)
            $this->resetPage('warehouses_page');

        return $warehouses;
    }
}
