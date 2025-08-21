<?php

namespace App\Livewire\CreatePurchase;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

class SearchProduct extends Component
{
    #[Validate('string|max:255')]
    public $search;

    #[Locked]
    public ?Collection $products = null;

    public function render()
    {
        return view('livewire.create-purchase.search-product');
    }

    public function updated($property)
    {
        if($property == 'search'){
            $this->searchProducts();
        }
    }

    public function searchProducts()
    {
        $this->validate();
        if(mb_strlen($this->search) > 0){
            $this->products = Product::where('name', 'LIKE', '%'.$this->search.'%')->get();
        } else {
            $this->reset('products');
        }
    }
}
