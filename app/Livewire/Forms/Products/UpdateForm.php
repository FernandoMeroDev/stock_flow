<?php

namespace App\Livewire\Forms\Products;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Locked;
use Livewire\Form;
use Illuminate\Validation\Rule;

class UpdateForm extends Form
{
    #[Locked]
    public ?Product $product = null;

    public $name;

    public $barcode;

    public $img = null;

    public $price;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:1', 'max:500', 'not_regex:/,/'],
            'barcode' => ['nullable', 'string', 'max:50', Rule::unique('products', 'barcode')->ignore($this->product?->id ?? 0)],
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

    public function setProduct(?Product $product)
    {
        if($product){
            $this->product = $product;
            $this->name = $product->name;
            $this->barcode = $product->barcode;
            if(is_null($this->product->img))
                $this->img = null;
            $this->price = $product->price;
        }
    }

    public function update()
    {
        $this->validate();
        if($this->product){
            if( ! $this->price  )
                $this->price = null;
            $inputs = $this->except(['img', 'img_path']);
            $inputs['img'] = $this->saveImg();
            $this->product->update($inputs);
            $this->reset();
        }
    }

    public function delete()
    {
        if($this->product){
            Storage::delete('products/'.$this->product->img);
            $this->product->delete();
        }
    }

    public function clearImg()
    {
        $this->reset('img');
        if($this->product?->img){
            Storage::delete('products/'.$this->product->img);
            $this->product->img = null;
            $this->product->save();
        }
    }

    private function saveImg(): ?string
    {
        if($this->img){
            if($this->product->img)
                Storage::delete('products/'.$this->product->img);
            return substr(
                $this->img->store(path: 'products'),
                offset: mb_strlen('products/')
            );
        }
        return $this->product->img;
    }
}
