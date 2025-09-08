<?php

namespace App\Livewire\Sales\Index;

use App\Livewire\Inventories\Index as InventoryIndex;
use App\Models\Inventory;

class CalcSales extends InventoryIndex
{
    public $inventories_ids = [];

    public function render()
    {
        $this->reset('inventories_ids');
        return view('livewire.sales.index.calc-sales', [
            'inventories' => $this->query()
        ]);
    }

    public function downloadSales()
    {
        $this->validate(rules: [
            'inventories_ids' => 'required|array|min:2',
            'inventories_ids.*' => 'required|boolean:strict'
        ], attributes: [
            'inventories_ids' => 'Inventarios',
            'inventories_ids.*' => 'Inventario #:position'
        ]);
        $inventories = [];
        foreach($this->inventories_ids as $id => $checked)
            if($checked) $inventories[] = $id;
        $this->reset('inventories_ids');
        $inventories = Inventory::whereIn('id', $inventories)->orderBy('saved_at')->get();
        $this->redirect(route('sales.download', [
            'inventory_a' => $inventories->get(0),
            'inventory_b' => $inventories->get(1),
        ]));
    }
}
