<?php

namespace App\Livewire\Purchases;

use App\Livewire\Forms\Purchases\UpdateForm;
use App\Models\Purchase;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class Edit extends Component
{
    public UpdateForm $form;

    #[Locked]
    public int $purchase_id;

    public function render()
    {
        return view('livewire.purchases.edit');
    }

    #[On('edit-purchase')]
    public function openModal($id)
    {
        $purchase = Purchase::find($id);
        $this->purchase_id = $purchase->id;
        $this->form->setPurchase($purchase);
        $this->modal('edit-purchase')->show();
    }

    public function update($id)
    {
        if($purchase = Purchase::find($id)){
            $this->form->update($purchase);
            $this->modal('edit-purchase')->close();
            $this->dispatch('edited');
        }
    }

    public function delete($id)
    {
        if($purchase = Purchase::find($id)){
            $this->form->delete($purchase);
            $this->modal('edit-purchase')->close();
            $this->dispatch('edited');
        }
    }
}
