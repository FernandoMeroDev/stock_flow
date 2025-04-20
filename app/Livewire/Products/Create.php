<?php

namespace App\Livewire\Products;

use App\Livewire\Forms\Products\StoreForm;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public StoreForm $form;

    public function render()
    {
        return view('livewire.products.create');
    }

    public function store()
    {
        $this->form->store();
        $this->modal('create-product')->close();
        $this->dispatch('created');
    }
}
