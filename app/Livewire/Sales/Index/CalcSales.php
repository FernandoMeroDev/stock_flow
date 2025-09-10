<?php

namespace App\Livewire\Sales\Index;

use App\Livewire\Inventories\Index as InventoryIndex;

class CalcSales extends InventoryIndex
{
    public function render()
    {
        return view('livewire.sales.index.calc-sales', [
            'inventories' => $this->query()
        ]);
    }
}
