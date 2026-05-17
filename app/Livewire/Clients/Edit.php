<?php

namespace App\Livewire\Clients;

use Livewire\Component;
use App\Livewire\Traits\Validation\Ownership as ValidateOwnership;
use App\Models\Client;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;

class Edit extends Component
{
    use ValidateOwnership;

    public Client $client;

    #[Validate('required|string|max:500')]
    public string $name = '';

    public function render()
    {
        return view('livewire.clients.edit');
    }

    #[On('edit-client')]
    public function editClient(mixed $id)
    {
        $this->client = $this->validate_ownership($id, Client::class);
        $this->name = $this->client->name;
        $this->modal('edit-client')->show();
    }

    public function update()
    {
        $this->client = $this->validate_ownership($this->client->id, Client::class);
        $this->validate();
        $this->client->update(['name' => $this->name]);
        $this->modal('edit-client')->close();
        $this->reset();
    }
}
