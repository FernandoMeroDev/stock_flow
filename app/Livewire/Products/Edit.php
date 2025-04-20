<?php

namespace App\Livewire\Products;

use App\Livewire\Forms\Products\UpdateForm;
use App\Models\Product;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public UpdateForm $form;

    public function render()
    {
        return view('livewire.products.edit');
    }

    #[On('edit-product')]
    public function openModal($product_id)
    {
        $product = Product::find($product_id);
        $this->form->setProduct($product);
        $this->modal('edit-product')->show();
    }

    public function update()
    {
        $this->form->update();
        $this->modal('edit-product')->close();
        $this->dispatch('edited');
    }

    public function delete()
    {
        $this->form->delete();
        $this->modal('edit-product')->close();
        $this->dispatch('edited');
    }

    public function clearImg()
    {
        $this->form->clearImg();
    }
}
