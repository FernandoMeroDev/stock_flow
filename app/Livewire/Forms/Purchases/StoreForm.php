<?php

namespace App\Livewire\Forms\Purchases;

use App\Models\Purchase;
use Livewire\Form;
use Illuminate\Database\Eloquent\Collection;
use App\Rules\Array\SameSize;
use Livewire\Attributes\Locked;

class StoreForm extends Form
{    
    #[Locked]
    public Collection $products;

    public $ids = [];

    public $amounts = [];

    protected function rules()
    {
        return [
            'ids' => 'required|array|min:1|max:9999',
            'ids.*' => 'required|integer|exists:products,id',
            'amounts' => ['required', 'array', 'min:1', 'max:9999', new SameSize('ids')],
            'amounts.*' => 'required|integer|min:1|max:9999',
        ];
    }

    protected function validationAttributes() 
    {
        return [
            'ids' => 'Productos',
            'ids.*' => 'Producto #:position',
            'amounts' => 'Cantidades',
            'amounts.*' => 'Cantidad #:position',
        ];
    }

    public function store()
    {
        $this->validate();
        foreach($this->ids as $key => $id){
            $old_purchase = Purchase::where('product_id', $id)->first();
            if($old_purchase)
                $old_purchase->update([
                    'count' => $old_purchase->count + $this->amounts[$key]
                ]);
            else
                Purchase::create([
                    'product_id' => $id,
                    'count' => $this->amounts[$key]
                ]);
        }
        $this->resetExcept(['products']);
        $this->products = new Collection();
    }
}
