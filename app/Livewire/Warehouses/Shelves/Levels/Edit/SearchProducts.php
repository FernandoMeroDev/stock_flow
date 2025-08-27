<?php

namespace App\Livewire\Warehouses\Shelves\Levels\Edit;

use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class SearchProducts extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $search;

    #[Locked]
    #[Reactive]
    public array $products;

    public function mount(array $products)
    {
        $this->products = $products;
    }

    private function searchProducts(): Paginator
    {
        $query = null;
        if( ! $this->search )
            $query = Product::where('id', 0); // Empty Eloquent collection
        else
            $query = Product::whereNotIn('id', array_keys($this->products))
                ->where('name','LIKE', "%$this->search%")
                ->orderBy('name');
        return $query->paginate(3, pageName: 'products_page');
    }

    public function render()
    {
        return view('livewire.warehouses.shelves.levels.edit.search-products', [
            'searchedProducts' => $this->searchProducts()
        ]);
    }

    public function updated($property)
    {
        if($property == 'search'){
            $this->resetPage('products_page');
        }
    }
}
