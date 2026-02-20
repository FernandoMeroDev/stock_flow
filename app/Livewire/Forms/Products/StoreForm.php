<?php

namespace App\Livewire\Forms\Products;

use App\Models\Presentation;
use App\Models\Product;
use Livewire\Form;

class StoreForm extends Form
{
    public $name;

    public $barcode;

    public $img;

    public $price;

    public $cash_box_id;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:1', 'max:500', 'not_regex:/,/'],
            'barcode' => 'nullable|string|max:50|unique:products,barcode',
            'img' => 'nullable|image|max:10240', // 10MB max
            'price' => 'required|decimal:0,2|min:0.01|max:9999.99',
            'cash_box_id' => 'required|exists:cash_boxes,id'
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'name' => 'Nombre',
            'barcode' => 'Código de Barras',
            'img' => 'Imagen',
            'price' => 'Precio',
            'cash_box_id' => 'Caja'
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
        $inputs = $this->except(['img']);
        $inputs['barcode'] = $inputs['barcode'] === '' ? null : $inputs['barcode'];
        $inputs['img'] = $this->saveImg();
        $product = Product::create($inputs);
        Presentation::create([
            'name' => '1 Unidad',
            'units' => 1,
            'price' => $inputs['price'],
            'base' => true,
            'product_id' => $product->id
        ]);
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
