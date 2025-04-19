<?php

namespace App\Livewire\Forms\Products;

use App\Models\Product;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UpdateForm extends Form
{
    #[Locked]
    public ?Product $product = null;

    #[Validate('required|string|max:500')]
    public $name = '';

    public function setProduct(?Product $product)
    {
        if($product){
            $this->product = $product;
            $this->name = $product->name;
        }
    }

    public function update()
    {
        $this->validate();
        if($this->product){
            $this->product->update($this->all());
        }
    }

    public function delete()
    {
        if($this->product)
            $this->product->delete();
    }
}
