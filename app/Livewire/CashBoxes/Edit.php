<?php

namespace App\Livewire\CashBoxes;

use App\Models\CashBox;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Edit extends Component
{
    public CashBox $cash_box;

    #[Validate('required|string|min:1|max:500')]
    public $name;

    public function mount(CashBox $cashBox)
    {
        $this->cash_box = $cashBox;
        $this->name = $cashBox->name;
    }

    public function render()
    {
        return view('livewire.cash-boxes.edit');
    }

    public function updatedName()
    {
        $this->validate();
        $this->cash_box->update(['name' => $this->name]);
    }

    public function destroy($id)
    {
        $cash_box = CashBox::find($id);
        if($cash_box) $cash_box->delete();
        $this->dispatch('cash-box-deleted');
    }
}
