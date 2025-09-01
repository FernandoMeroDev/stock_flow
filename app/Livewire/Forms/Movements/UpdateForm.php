<?php

namespace App\Livewire\Forms\Movements;

use App\Models\Movement;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UpdateForm extends Form
{
    #[Locked]
    public ?Movement $movement = null;

    #[Locked]
    public ?string $product_name = null;

    #[Locked]
    public ?string $movement_type = null;

    #[Validate('required|integer|min:1|max:9999', attribute: 'Cantidad')]
    public $count;

    public function setMovement(?Movement $movement)
    {
        if($movement){
            $this->$movement = $movement;
            $this->product_name = $movement->product->name;
            $this->count = $movement->count;
            $this->movement_type = $movement->type;
        }
    }

    public function update(Movement $movement)
    {
        $this->validate();
        if($movement){
            $inputs = $this->only(['count']);
            $movement->update($inputs);
            $this->reset();
        }
    }

    public function delete(Movement $movement)
    {
        $movement?->delete();
    }
}
