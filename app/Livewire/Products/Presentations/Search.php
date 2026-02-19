<?php

namespace App\Livewire\Products\Presentations;

use App\Models\Presentation;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Search extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $search;
    
    private function searchPresentations(): Paginator
    {
        $query = null;
        if( ! $this->search )
            $query = Presentation::where('id', 0); // Empty Eloquent collection
        else {
            $query = Presentation::join('products', 'products.id', '=', 'presentations.product_id')
                ->select('presentations.*')
                ->whereRaw(
                    "CONCAT(presentations.name, ' ', products.name) LIKE ?", ["%$this->search%"]
                )->orderBy('name');
        }
        return $query->paginate(3, pageName: 'presentations_page');
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
