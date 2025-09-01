<?php

namespace App\Livewire\Forms\Movements;

use App\Models\Movement;
use Livewire\Form;
use Illuminate\Database\Eloquent\Collection;
use App\Rules\Array\SameSize;
use Livewire\Attributes\Locked;
use Illuminate\Validation\Rule;

class StoreForm extends Form
{
    #[Locked]
    public Collection $products;

    public $ids = [];

    public $amounts = [];

    public $types = [];

    protected function rules()
    {
        return [
            'ids' => 'required|array|min:1|max:9999',
            'ids.*' => 'required|integer|exists:products,id',
            'amounts' => ['required', 'array', 'min:1', 'max:9999', new SameSize('ids')],
            'amounts.*' => 'required|integer|min:1|max:9999',
            'types' => ['required', 'array', 'min:1', 'max:9999', new SameSize('ids')],
            'types.*' => ['required', Rule::in(['i', 'o'])],
        ];
    }

    protected function validationAttributes() 
    {
        return [
            'ids' => 'Productos',
            'ids.*' => 'Producto #:position',
            'amounts' => 'Cantidades',
            'amounts.*' => 'Cantidad #:position',
            'types' => 'Tipo',
            'types.*' => 'Tipo #:position',
        ];
    }

    public function store()
    {
        $this->validate();
        foreach($this->ids as $key => $id){
            $old_movement = Movement::where('product_id', $id)
                ->where('type', $this->types[$key])->first();
            if($old_movement)
                $old_movement->update([
                    'count' => $old_movement->count + $this->amounts[$key]
                ]);
            else
                Movement::create([
                    'type' => $this->types[$key],
                    'product_id' => $id,
                    'count' => $this->amounts[$key]
                ]);
        }
        $this->resetExcept(['products']);
        $this->products = new Collection();
    }
}
