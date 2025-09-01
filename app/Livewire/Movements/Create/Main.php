<?php

namespace App\Livewire\Movements\Create;

use App\Livewire\Forms\Movements\StoreForm;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class Main extends Component
{
    public StoreForm $form;

    public function mount()
    {
        $this->form->products = new Collection();
    }

    public function render()
    {
        return view('livewire.movements.create.main');
    }

    #[On('add-product')]
    public function addProduct($id, $idType = 'primaryKey')
    {
        $product = $idType == 'barcode'
            ? Product::where('barcode', $id)->first()
            : Product::find($id);
        if($product){
            foreach($this->form->products->keys() as $i){
                if(
                    $this->form->products->get($i)->id == $product->id
                    && $this->form->types[$i] == 'i'
                ){
                    $this->form->amounts[$i] += 1;
                    return;
                }
            }
            $this->form->products->push($product);
            $this->form->ids[] = $product->id;
            $this->form->amounts[] = 1;
            $this->form->types[] = 'i';
        }
    }

    public function removeProduct($id)
    {
        $product = Product::find($id);
        if($product){
            foreach($this->form->products->keys() as $i){
                if($this->form->products->get($i)->id == $product->id){
                    if($this->form->amounts[$i] <= 1){
                        $this->form->products->pull($i);
                        unset($this->form->ids[$i]);
                        unset($this->form->amounts[$i]);
                        unset($this->form->types[$i]);
                    } else {
                        $this->form->amounts[$i] -= 1;
                    }
                    return;
                }
            }
        }
    }

    public function store()
    {
        $this->form->store();
    }
}
