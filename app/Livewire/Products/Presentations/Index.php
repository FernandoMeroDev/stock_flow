<?php

namespace App\Livewire\Products\Presentations;

use App\Models\Presentation;
use App\Models\Product;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    public Product $product;

    public function mount(Product $product)
    {
        $this->product = $product;
    }

    #[On('edit-product')]
    public function refreshPresentations($product_id)
    {
        $this->product = Product::find($product_id);
    }

    public function render()
    {
        return view('livewire.products.presentations.index');
    }

    public function generateNew()
    {
        Presentation::create([
            'name' => 'Nueva Presentación',
            'units' => 1,
            'price' => 0.01,
            'base' => false,
            'product_id' => $this->product->id
        ]);
    }
}
