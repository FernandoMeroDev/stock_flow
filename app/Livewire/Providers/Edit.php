<?php

namespace App\Livewire\Providers;

use App\Models\Provider;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use App\Livewire\Traits\Validation\Ownership as ValidateOwnership;

class Edit extends Component
{
    use ValidateOwnership;

    public Provider $provider;

    #[Validate('required|string|max:500')]
    public string $name = '';

    public function render()
    {
        return view('livewire.providers.edit');
    }

    #[On('edit-provider')]
    public function editProvider(mixed $id)
    {
        $this->provider = $this->validate_ownership($id, Provider::class);
        $this->name = $this->provider->name;
        $this->modal('edit-provider')->show();
    }

    public function update()
    {
        $this->provider = $this->validate_ownership($this->provider->id, Provider::class);
        $this->validate();
        $this->provider->update(['name' => $this->name]);
        $this->modal('edit-provider')->close();
        $this->reset();
    }
}
