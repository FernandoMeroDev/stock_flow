<?php

namespace App\Livewire\Forms\Products;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UpdateForm extends Form
{
    #[Locked]
    public ?Product $product = null;

    #[Validate('required|string|max:500', attribute: 'Nombre')]
    public $name;

    #[Validate('nullable|mimes:jpg,png,webp|max:5120', attribute: 'Imagen')] // 5MB max
    public $img = null;

    #[Validate('nullable|string|max:50', attribute: 'Código')]
    public $barcode;

    #[Validate('nullable|decimal:0,3|min:0.001|max:9999.999', attribute: 'Precio')]
    public $price;

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
