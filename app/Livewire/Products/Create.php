<?php

namespace App\Livewire\Products;

use App\Livewire\Forms\Products\StoreForm;
use Livewire\Component;

class Create extends Component
{
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
