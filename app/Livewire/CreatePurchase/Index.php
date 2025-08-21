<?php

namespace App\Livewire\CreatePurchase;

use Livewire\Component;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;

class Index extends Component
{
    #[Locked]
    public Collection $products;

    public $ids = [];

    public $prices = [];

    public $amounts = [];

    public function mount()
    {
        $this->products = new Collection();
    }

    public function render()
    {
        return view('livewire.create-purchase.index');
    }

    #[On('add-product')]
    public function addProduct($id, $idType = 'primaryKey')
    {
        $product = $idType == 'barcode'
            ? Product::where('barcode', $id)->first()
            : Product::find($id);
        if($product){
            foreach($this->products->keys() as $i){
                if($this->products->get($i)->id == $product->id){
                    $this->amounts[$i] += 1;
                    return;
                }
            }
            $this->products->push($product);
            $this->ids[] = $product->id;
            $this->prices[] = 0;
            $this->amounts[] = 1;
        }
    }

    public function removeProduct($id)
    {
        $product = Product::find($id);
        if($product){
            foreach($this->products->keys() as $i){
                if($this->products->get($i)->id == $product->id){
                    if($this->amounts[$i] <= 1){
                        $this->products->pull($i);
                        unset($this->ids[$i]);
                        unset($this->prices[$i]);
                        unset($this->amounts[$i]);
                    } else {
                        $this->amounts[$i] -= 1;
                    }
                    return;
                }
            }
        }
    }
}
