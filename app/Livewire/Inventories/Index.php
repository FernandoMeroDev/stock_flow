<?php

namespace App\Livewire\Inventories;

use App\Models\Inventory;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;

    #[Validate('date|before:date_to', attribute: 'Fecha Inicial')]
    public $date_from;

    #[Validate('date|before_or_equal:today|after:date_from', attribute: 'Fecha Final')]
    public $date_to;

    #[Locked]
    public $safe_filters = [];

    public function mount()
    {
        $this->date_from = date('Y-m-d', mktime(hour: 12, day: -7));
        $this->date_to = date('Y-m-d');
    }

    public function render()
    {
        return view('livewire.inventories.index', [
            'inventories' => $this->query()
        ]);
    }

    private function query()
    {
        $inventories = Inventory::select('*');
        if(isset($this->safe_filters['date_from']))
            $inventories->where('saved_at', '>=', $this->safe_filters['date_from'] . ' 00:00:01');
        if(isset($this->safe_filters['date_to']))
            $inventories->where('saved_at', '<=', $this->safe_filters['date_to'] . ' 23:59:59');
        $inventories = $inventories->orderBy('saved_at', 'desc')->paginate(15, pageName: 'inventories_page');

        if($inventories->isEmpty() && $inventories->currentPage() !== 1)
            $this->resetPage('inventories_page');
        
        return $inventories;
    }

    public function updated($property)
    {
        if($property == 'date_from' || $property == 'date_to'){
            $this->validate();
            $this->safe_filters['date_from'] = $this->date_from;
            $this->safe_filters['date_to'] = $this->date_to;
        }
    }
}
