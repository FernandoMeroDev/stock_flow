<?php

namespace App\Livewire\Movements;

use App\Livewire\Forms\Movements\UpdateForm;
use App\Models\Movement;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class Edit extends Component
{
    public UpdateForm $form;

    #[Locked]
    public int $movement_id;

    public function render()
    {
        return view('livewire.movements.edit');
    }

    #[On('edit-movement')]
    public function openModal($id)
    {
        $movement = Movement::find($id);
        $this->movement_id = $movement->id;
        $this->form->setMovement($movement);
        $this->modal('edit-movement')->show();
    }

    public function update($id)
    {
        if($movement = Movement::find($id)){
            $this->form->update($movement);
            $this->modal('edit-movement')->close();
            $this->dispatch('edited');
        }
    }

    public function delete($id)
    {
        if($movement = Movement::find($id)){
            $this->form->delete($movement);
            $this->modal('edit-movement')->close();
            $this->dispatch('edited');
        }
    }
}
