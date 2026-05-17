<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Create extends Component
{
    #[Validate('required|string|max:500')]
    public string $name;

    public function render()
    {
        return view('livewire.clients.create');
    }

    public function store()
    {
        $this->validate();
        Client::create([
            'name' => $this->name,
            'user_id' => Auth::user()->id
        ]);
        $this->reset();
        $this->modal('create-client')->close();
    }
}
