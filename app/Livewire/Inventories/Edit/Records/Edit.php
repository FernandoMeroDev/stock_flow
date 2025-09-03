<?php

namespace App\Livewire\Inventories\Edit\Records;

use App\Livewire\Forms\Inventories\Records\UpdateForm;
use App\Models\InventoryProduct;
use App\Models\Product;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Edit extends Component
{
    use WithPagination, WithoutUrlPagination;

    public UpdateForm $form;

    public $search;

    public function render()
    {
        return view('livewire.inventories.edit.records.edit', [
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
        $products = $products->paginate(3, pageName: 'records.edit.products_page');

        if($products->isEmpty() && $products->currentPage() !== 1)
            $this->resetPage('records.edit.products_page');

        return $products;
    }

    public function setProduct($id)
    {
        if($product = Product::find($id))
            $this->form->setProduct($product);
    }

    #[On('edit-inventory-record')]
    public function openModal($id)
    {
        if($inventory_product = InventoryProduct::find($id)){
            $this->form->setInventoryRecord($inventory_product);
            $this->modal('edit-inventory-record')->show();
        }
    }

    public function update()
    {
        $this->form->update();
        $this->modal('edit-inventory-record')->close();
        $this->dispatch('edited');
    }

    public function delete()
    {
        $this->form->delete();
        $this->modal('edit-inventory-record')->close();
        $this->dispatch('edited');
    }
}
