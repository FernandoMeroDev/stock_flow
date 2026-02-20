<?php

namespace App\Livewire\CashBoxes;

use App\Models\CashBox;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.cash-boxes.index', [
            'cash_boxes' => CashBox::all()
        ]);
    }

    public function createNew()
    {
        CashBox::create(['name' => 'Nueva Caja']);
    }
}
