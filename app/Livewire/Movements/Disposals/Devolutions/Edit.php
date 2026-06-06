<?php

namespace App\Livewire\Movements\Disposals\Devolutions;

use App\Livewire\Forms\Movements\Disposals\Devolutions\UpdateForm;
use App\Models\Movements\DisposalDevolution;
use Livewire\Component;

class Edit extends Component
{
    public UpdateForm $form;

    public function render()
    {
        return view('livewire.movements.disposals.devolutions.edit');
    }

    public function mount(DisposalDevolution $disposalDevolution)
    {
        $this->form->setDisposalDevolution($disposalDevolution);
    }
}
