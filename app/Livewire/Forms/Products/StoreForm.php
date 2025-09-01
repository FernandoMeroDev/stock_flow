<?php

namespace App\Livewire\Forms\Products;

use App\Models\Product;
use Livewire\Attributes\Validate;
use Livewire\Form;

class StoreForm extends Form
{
    #[Validate('required|string|max:500', attribute: 'Nombre')]
    public $name;

    #[Validate('nullable|string|max:50', attribute: 'Imagen')]
    public $barcode;

    #[Validate('nullable|image|max:5120', attribute: 'Código')] // 5MB max
    public $img;

    #[Validate('nullable|decimal:0,3|min:0.001|max:9999.999', attribute: 'Precio')]
    public $price;

    public function store()
    {
        $this->validate();
        if( ! $this->price  )
            $this->price = null;
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
