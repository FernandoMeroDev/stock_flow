<?php

namespace App\Livewire\Forms\Products;

use App\Models\Product;
use Livewire\Attributes\Validate;
use Livewire\Form;

class StoreForm extends Form
{
    #[Validate('required|string|max:500')]
    public $name;

    public function store()
    {
        $this->validate();
        Product::create(
            $this->pull()
        );
    }
}
