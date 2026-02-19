<?php

namespace App\Livewire\Products\Presentations;

use App\Models\Presentation;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Edit extends Component
{
    public Presentation $presentation;

    #[Validate]
    public $name;

    #[Validate]
    public $units;

    #[Validate]
    public $price;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:1', 'max:500', 'not_regex:/,/'],
            'units' => 'required|integer|min:1|max:9999',
            'price' => 'required|decimal:0,2|min:0.01|max:9999.99'
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'name' => 'Nombre',
            'units' => 'Unidades',
            'price' => 'Precio'
        ];
    }

    public function mount(Presentation $presentation)
    {
        $this->presentation = $presentation;
        $this->name = $presentation->name;
        $this->units = $presentation->units;
        $this->price = $presentation->price;
    }

    public function render()
    {
        return view('livewire.products.presentations.edit');
    }

    public function destroy($id)
    {
        $presentation = Presentation::find($id);
        if( ! $presentation->base)
            $presentation->delete();
        $this->dispatch('presentation-deleted');
    }

    public function updated($name, $value)
    {
        $this->validate();
        if($name != 'presentation')
            $this->presentation->update([
                $name => $value
            ]);
    }
}
