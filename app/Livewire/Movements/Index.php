<?php

namespace App\Livewire\Movements;

use App\Models\Movement;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search;

    public function render()
    {
        return view('livewire.movements.index', [
            'movements' => $this->query()
        ]);
    }

    private function query()
    {
        $products = Product::getTableName();
        $movements = Movement::getTableName();
        $movements = Movement::join(
            $products, "$products.id", "=", "$movements.product_id"
        )->select(
            "$products.name as product_name", "$movements.*"
        )->where('name', 'LIKE', "%$this->search%")
            ->orderBy('name')->paginate(15, pageName: 'movements_page');

        if($movements->isEmpty() && $movements->currentPage() !== 1)
            $this->resetPage('movements_page');

        return $movements;
    }

    public function delete()
    {
        foreach(Movement::all() as $movement)
                $movement->delete();
    }
}
