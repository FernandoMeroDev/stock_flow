<?php

namespace App\Livewire\Movements\Create;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Livewire\Component;
use Livewire\WithPagination;

class SearchProducts extends Component
{
    use WithPagination;

    public $search;

    private function searchProducts(): Paginator
    {
        $query = null;
        if( ! $this->search )
            $query = Product::where('id', 0); // Empty Eloquent collection
        else
            $query = Product::where('name','LIKE', "%$this->search%")->orderBy('name');
        return $query->paginate(3, pageName: 'products_page');
    }

    public function render()
    {
        return view('livewire.movements.create.search-products', [
            'products' => $this->searchProducts()
        ]);
    }

    public function updated($property)
    {
        if($property == 'search'){
            $this->resetPage('products_page');
        }
    }
}
