<?php

namespace App\Livewire\Warehouses\Shelves\Levels\Edit;

use App\Livewire\Forms\Warehouses\Shelves\Levels\Edit\UpdateForm;
use App\Models\Product;
use App\Models\Shelves\Level;
use Illuminate\Support\Arr;
use Livewire\Attributes\On;
use Livewire\Attributes\Renderless;
use Livewire\Component;

class Main extends Component
{
    public UpdateForm $form;

    public $drag_and_drop_enabled = false;

    public function mount(Level $level)
    {
        $this->form->setProducts($level);
    }

    public function render()
    {
        return view('livewire.warehouses.shelves.levels.edit.main');
    }

    public function remove($id): void
    {
        if(Arr::exists($this->form->products, $id))
            Arr::pull($this->form->products, $id);
        $this->js('$js.register_unsaved_changes');
    }

    #[On('add-product')]
    public function add($id): void
    {
        if( ! Arr::exists($this->form->products, $id) ){
            $product = Product::find($id);
            $this->form->products[$product->id] = [
                'name' => $product->name,
                'count' => 0
            ];
        };
        $this->js('$js.register_unsaved_changes');
    }

    #[Renderless]
    public function updateCount($id, $count): void
    {
        if(Arr::exists($this->form->products, $id))
            $this->form->products[$id]['count'] = $count;
        $this->js('$js.register_unsaved_changes');
    }

    #[Renderless]
    public function reorderProducts($ordered_products): void
    {
        $valid = true;
        $products = [];
        foreach($ordered_products as $id){
            if(Arr::exists($this->form->products, $id))
                $products[$id] = $this->form->products[$id];
            else
                $valid = false;
        }
        if($valid){
            $this->form->products = $products;
            $this->js('$js.register_unsaved_changes');
        }
    }

    public function refresh_products(): void
    {
        $products = [];
        foreach($this->form->products as $id => $product_old){
            $product = Product::find($id);
            if($product) $products[$id] = [
                'name' => $product->name,
                'count' => $product_old['count']
            ];
        }
        $this->form->products = $products;
    }

    #[Renderless]
    #[On('enabled-drag-and-drop')]
    public function registerDragAndDropActivation()
    {
        $this->drag_and_drop_enabled = true;
    }

    public function update(): void
    {
        $this->form->update();
        $this->js('$js.remove_unsaved_changes');
        if($this->drag_and_drop_enabled)
            $this->redirect(route('levels.edit', $this->form->level->id));
    }

    public function empty(): void
    {
        $this->form->empty();
        $this->js('$js.register_unsaved_changes');
    }
}
