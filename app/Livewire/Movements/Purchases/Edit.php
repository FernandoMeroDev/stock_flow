<?php

namespace App\Livewire\Movements\Purchases;

use App\Models\Movements\Purchase;
use Livewire\Component;
use App\Livewire\Forms\Movements\Purchases\UpdateForm;
use App\Models\Provider;

class Edit extends Component
{
    public UpdateForm $form;

    public function mount(Purchase $purchase)
    {
        $this->form->setPurchase($purchase);
    }

    public function render()
    {
        return view('livewire.movements.purchases.edit', [
            'providers' => Provider::queryOwnModels()->get(),
        ]);
    }
}
