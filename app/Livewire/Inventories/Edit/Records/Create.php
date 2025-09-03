<?php

namespace App\Livewire\Inventories\Edit\Records;

use App\Livewire\Forms\Inventories\Records\StoreForm;
use App\Models\Inventory;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Create extends Component
{
    use WithPagination, WithoutUrlPagination;

    public StoreForm $form;

    public $search;

    public function mount(Inventory $inventory)
    {
        $this->form->setInventory($inventory);
        $this->form->setWarehouseExistences();
    }

    public function render()
    {
        return view('livewire.inventories.edit.records.create', [
            'products' => $this->query()
        ]);
    }

    private function query()
    {
        $products = null;
        if( ! $this->search )
            $products = Product::where('id', 0); // Empty Eloquent collection
        else
            $products = Product::where('name','LIKE', "%$this->search%")->orderBy('name');
        $products = $products->paginate(3, pageName: 'records.create.products_page');

        if($products->isEmpty() && $products->currentPage() !== 1)
            $this->resetPage('records.create.products_page');

        return $products;
    }

    public function setProduct($id)
    {
        if($product = Product::find($id))
            $this->form->setProduct($product);
    }

    public function store()
    {
        $this->form->store();
        $this->modal('create-inventory-record')->close();
        $this->dispatch('created');
    }
}
