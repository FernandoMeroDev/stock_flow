<?php

namespace App\Livewire\Products;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Search extends Component
{
    use WithPagination, WithoutUrlPagination;

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
        return view('livewire.products.search', [
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
