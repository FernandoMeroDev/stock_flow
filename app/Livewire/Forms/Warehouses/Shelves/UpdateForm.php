<?php

namespace App\Livewire\Forms\Warehouses\Shelves;

use App\Models\Shelf;
use App\Models\Shelves\Level;
use App\Rules\Warehouses\Shelves\Create\UniqueNumber;
use Livewire\Attributes\Locked;
use Livewire\Form;

class UpdateForm extends Form
{
    #[Locked]
    public Shelf $shelf;

    public $number;

    public $levels_count;

    protected function rules()
    {
        return [
            'number' => [
                'required', 'integer', 'min:1', 'max:9999',
                new UniqueNumber($this->shelf->warehouse, except: $this->shelf->id)
            ],
            'levels_count' => 'required|integer|min:1|max:50'
        ];
    }

    protected function validationAttributes() 
    {
        return [
            'number' => 'Número',
            'levels_count' => 'Pisos',
        ];
    }

    public function setShelf(Shelf $shelf)
    {
        $this->shelf = $shelf;
        $this->number = $shelf->number;
        $this->levels_count = $shelf->levels->count() - 1;
    }

    public function update()
    {
        $this->validate();
        $this->shelf->update(['number' => $this->number]);

        $levels_count = intval($this->levels_count);
        $old_levels_count = $this->shelf->levels->count();

        $levels = $this->shelf->levels->sortBy('number');

        if($levels_count < $old_levels_count - 1){
            for($i = $levels_count + 1; $i < $old_levels_count; $i++){
                $levels->get($i)->delete();
            }
        } else if($levels_count > $old_levels_count - 1) {
            for($i = $old_levels_count; $i <= $levels_count; $i++){
                Level::create([
                    'number' => $i,
                    'shelf_id' => $this->shelf->id
                ]);
            }
        }
    }
}
