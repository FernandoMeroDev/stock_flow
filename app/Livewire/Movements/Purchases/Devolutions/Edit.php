<?php

namespace App\Livewire\Movements\Purchases\Devolutions;

use App\Livewire\Forms\Movements\Purchases\Devolutions\UpdateForm;
use App\Models\Movements\PurchaseDevolution;
use Livewire\Component;

class Edit extends Component
{
    public UpdateForm $form;

    public function mount(PurchaseDevolution $purchaseDevolution)
    {
        $this->form->setPurchaseDevolution($purchaseDevolution);
    }

    public function render()
    {
        return view('livewire.movements.purchases.devolutions.edit');
    }
}
