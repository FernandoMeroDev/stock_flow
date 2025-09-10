<?php

namespace App\Livewire\Forms\Products;

use App\Models\Product;
use Livewire\Form;

class StoreForm extends Form
{
    public $name;

    public $barcode;

    public $img;

    public $price;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:1', 'max:500', 'not_regex:/,/'],
            'barcode' => 'nullable|string|max:50|unique:products,barcode',
            'img' => 'nullable|image|max:10240', // 10MB max
            'price' => 'nullable|decimal:0,3|min:0.001|max:9999.999',
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'name' => 'Nombre',
            'barcode' => 'Código de Barras',
            'img' => 'Imagen',
            'price' => 'Precio',
        ];
    }

    protected function messages(): array
    {
        return [
            'name.not_regex' => 'El nombre no puede contener comas.'
        ];
    }

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
