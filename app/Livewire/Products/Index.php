<?php

namespace App\Livewire\Products;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search;

    public function render()
    {
        return view('livewire.products.index', [
            'products' => $this->query()
        ]);
    }

    private function query()
    {
        $products = Product::where('name', 'LIKE', "%$this->search%")
            ->orderBy('name')->paginate(15, pageName: 'products_page');

        if($products->isEmpty() && $products->currentPage() !== 1)
            $this->resetPage('products_page');

        return $products;
    }
}
