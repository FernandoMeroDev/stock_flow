<?php

namespace App\Livewire\Forms\Warehouses;

use App\Models\Warehouse;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UpdateForm extends Form
{    
    #[Locked]
    public Warehouse $warehouse;

    #[Validate('required|string|max:255')]
    public $name;

    public function setWarehouse(Warehouse $warehouse)
    {
        $this->warehouse = $warehouse;
        $this->name = $warehouse->name;
    }

    public function update()
    {
        $this->validate();
        $inputs = $this->only(['name']);
        $this->warehouse->update($inputs);
    }
}
