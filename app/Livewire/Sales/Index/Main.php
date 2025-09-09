<?php

namespace App\Livewire\Sales\Index;

use App\Models\Sale;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Main extends Component
{
    use WithPagination, WithoutUrlPagination;

    #[Validate('required|exists:warehouses,id', attribute: 'Bodega')]
    public $warehouse_id;

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
        $this->safe_filters['date_from'] = $this->date_from;
        $this->safe_filters['date_to'] = $this->date_to;
    }

    public function render()
    {
        return view('livewire.sales.index.main', [
            'sales' => $this->query()
        ]);
    }
    
    private function query(): LengthAwarePaginator
    {
        $sales = Sale::selectRaw('DISTINCT DATE(saved_at) as date');
        if(isset($this->safe_filters['warehouse_id']))
            $sales->where('warehouse_id', $this->warehouse_id);
        else
            $sales->where('id', 0); // Empty Collection
        if(isset($this->safe_filters['date_from']))
            $sales->where('saved_at', '>=', $this->safe_filters['date_from'] . ' 00:00:01');
        if(isset($this->safe_filters['date_to']))
            $sales->where('saved_at', '<=', $this->safe_filters['date_to'] . ' 23:59:59');
        $sales = $sales->orderBy('date', 'desc')->paginate(15, pageName: 'sales_page');

        if($sales->isEmpty() && $sales->currentPage() !== 1)
            $this->resetPage('sales_page');

        return $sales;
    }

    public function updated($property, $value)
    {
        $this->validate();
        $this->safe_filters['date_from'] = $this->date_from;
        $this->safe_filters['date_to'] = $this->date_to;
        $this->safe_filters['warehouse_id'] = $this->warehouse_id;
    }
}
