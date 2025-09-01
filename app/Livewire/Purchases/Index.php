<?php

namespace App\Livewire\Purchases;

use App\Models\Product;
use App\Models\Purchase;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search;

    public function render()
    {
        return view('livewire.purchases.index', [
            'purchases' => $this->query()
        ]);
    }

    private function query()
    {
        $products = Product::getTableName();
        $purchases = Purchase::getTableName();
        $purchases = Purchase::join(
            $products, "$products.id", "=", "$purchases.product_id"
        )->select(
            "$products.name as product_name", "$purchases.*"
        )->where('name', 'LIKE', "%$this->search%")
            ->orderBy('name')->paginate(15, pageName: 'purchases_page');

        if($purchases->isEmpty() && $purchases->currentPage() !== 1)
            $this->resetPage('purchases_page');

        return $purchases;
    }

    public function delete()
    {
        foreach(Purchase::all() as $purchase)
                $purchase->delete();
    }
}
