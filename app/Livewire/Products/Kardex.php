<?php

namespace App\Livewire\Products;

use App\Livewire\Traits\Pagination\CanPaginateManually;
use App\Models\Movements\Movement;
use App\Models\Product;
use App\Models\Warehouse;
use Livewire\Component;
use Livewire\WithPagination;

class Kardex extends Component
{
    use WithPagination, CanPaginateManually;

    public Product $product;

    public int $perPage = 15;

    public int $warehouse_id = 0;

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->warehouse_id = Warehouse::orderBy('name')->first()->id;
    }

    public function render()
    {
        return view('livewire.products.kardex', [
            'warehouses' => Warehouse::all(),
            'movements' => $this->query()
        ]);
    }

    private function query()
    {
        $movements = Movement::where('product_id', $this->product->id)
            ->where('warehouse_id', $this->warehouse_id)
            ->orderBy('created_at', 'desc')
            ->get();
        return $this->paginate($movements, $this->perPage);
    }
}
