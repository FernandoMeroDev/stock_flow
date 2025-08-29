<?php

namespace App\Livewire\Forms\Purchases;

use App\Models\Purchase;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UpdateForm extends Form
{
    #[Locked]
    public ?Purchase $purchase = null;

    #[Locked]
    public ?string $product_name = null;

    #[Validate('required|integer|min:1|max:9999', attribute: 'Cantidad')]
    public $count;

    public function setPurchase(?Purchase $purchase)
    {
        if($purchase){
            $this->$purchase = $purchase;
            $this->product_name = $purchase->product->name;
            $this->count = $purchase->count;
        }
    }

    public function update(Purchase $purchase)
    {
        $this->validate();
        if($purchase){
            $inputs = $this->only(['count']);
            $purchase->update($inputs);
            $this->reset();
        }
    }

    public function delete(Purchase $purchase)
    {
        $purchase?->delete();
    }
}
