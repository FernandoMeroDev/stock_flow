<?php

namespace App\Livewire\Products;

use App\Livewire\Traits\Pagination\CanPaginateManually;
use App\Models\Movements\Movement;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class Kardex extends Component
{
    use WithPagination, CanPaginateManually;

    public Product $product;

    public int $perPage = 15;

    public function mount(Product $product)
    {
        $this->product = $product;
    }

    public function render()
    {
        return view('livewire.products.kardex', [
            'movements' => $this->query()
        ]);
    }

    private function query()
    {
        $movements = Movement::where('product_id', $this->product->id)
            ->orderBy('created_at', 'desc')
            ->get();
        return $this->paginate($movements, $this->perPage);
    }
}
