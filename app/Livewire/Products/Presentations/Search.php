<?php

namespace App\Livewire\Products\Presentations;

use App\Models\Presentation;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Search extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $search;

    public int $warehouse_id = 0;

    public function mount(int $warehouseId)
    {
        $this->warehouse_id = $warehouseId;
    }
    
    private function searchPresentations(): Paginator
    {
        $query = null;
        if( ! $this->search )
            $query = Presentation::where('id', 0); // Empty Eloquent collection
        else {
            $warehouse_id = $this->warehouse_id;
            $query = Presentation::join('products', 'products.id', '=', 'presentations.product_id')
                ->join('product_warehouse', function (JoinClause $join) use ($warehouse_id) {
                    $join->on('product_warehouse.product_id', '=', 'products.id')
                        ->where('product_warehouse.warehouse_id', '=', $warehouse_id);
                })
                ->select('presentations.*', 'product_warehouse.stock as total_stock')
                ->whereRaw(
                    "CONCAT(presentations.name, ' ', products.name) LIKE ?", ["%$this->search%"]
                )->orderBy('name');
        }
        return $query->paginate(10, pageName: 'presentations_page');
    }

    public function render()
    {
        return view('livewire.products.presentations.search', [
            'presentations' => $this->searchPresentations()
        ]);
    }

    public function updated($property)
    {
        if($property == 'search'){
            $this->resetPage('presentations_page');
        }
    }
}
