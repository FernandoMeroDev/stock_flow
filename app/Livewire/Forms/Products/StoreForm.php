<?php

namespace App\Livewire\Forms\Products;

use App\Models\Product;
use Livewire\Attributes\Validate;
use Livewire\Form;

class StoreForm extends Form
{
    #[Validate('required|string|max:500')]
    public $name;

    #[Validate('nullable|string|max:50')]
    public $barcode;

    #[Validate('nullable|image|max:5120')] // 5MB max
    public $img;

    public function store()
    {
        $this->validate();
        $inputs = $this->except('img');
        $inputs['img'] = $this->saveImg();
        Product::create($inputs);
        $this->reset();
    }

    private function saveImg(): ?string
    {
        if($this->img){
            return substr(
                $this->img->store(path: 'products'),
                offset: mb_strlen('products/')
            );
        }
        return null;
    }
}
