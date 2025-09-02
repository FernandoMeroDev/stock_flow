<?php

namespace App\Livewire\Forms\Inventories;

use App\Models\Inventory;
use Livewire\Attributes\Locked;
use Livewire\Form;

class UpdateForm extends Form
{
    #[Locked]
    public Inventory $inventory;

    public $saved_at;

    protected function rules()
    {
        $today = date('Y-m-d H:i:s');
        return [
            'saved_at' => "required|date|before_or_equal:{$today}",
        ];
    }

    protected function validationAttributes() 
    {
        return [
            'saved_at' => 'Fecha',
        ];
    }

    public function setInventory(Inventory $inventory)
    {
        $this->inventory = $inventory;
        $this->saved_at = $inventory->saved_at;
    }

    public function update()
    {
        $this->validate();
        $this->inventory->update($this->only(['saved_at']));
    }
}
