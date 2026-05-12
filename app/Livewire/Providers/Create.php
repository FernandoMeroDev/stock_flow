<?php

namespace App\Livewire\Providers;

use App\Models\Provider;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Create extends Component
{
    #[Validate('required|string|max:500')]
    public string $name;

    public function render()
    {
        return view('livewire.providers.create');
    }

    public function store()
    {
        $this->validate();
        Provider::create([
            'name' => $this->name,
            'user_id' => Auth::user()->id
        ]);
        $this->dispatch('created-provider');
    }
}
