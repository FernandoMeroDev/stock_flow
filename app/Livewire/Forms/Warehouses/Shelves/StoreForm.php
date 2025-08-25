<?php

namespace App\Livewire\Forms\Warehouses\Shelves;

use App\Models\Shelf;
use App\Models\Shelves\Level;
use App\Models\Warehouse;
use App\Rules\Warehouses\Shelves\Create\UniqueNumber;
use Livewire\Attributes\Locked;
use Livewire\Form;

class StoreForm extends Form
{    
    #[Locked]
    public Warehouse $warehouse;

    public $number;

    public $levels_count;

    protected function rules()
    {
        return [
            'number' => [
                'required', 'integer', 'min:1', 'max:9999',
                new UniqueNumber($this->warehouse)
            ],
            'levels_count' => 'required|integer|min:1|max:50'
        ];
    }

    public function setWarehouse(Warehouse $warehouse)
    {
        $this->warehouse = $warehouse;
    }

    public function store()
    {
        $this->validate();
        $shelf = Shelf::create([
            'number' => $this->number,
            'warehouse_id' => $this->warehouse->id
        ]);
        for($i = 1; $i <= $this->levels_count; $i++){
            Level::create([
                'number' => $i,
                'shelf_id' => $shelf->id
            ]);
        }
        $this->resetExcept('warehouse');
    }
}
