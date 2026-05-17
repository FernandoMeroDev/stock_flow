<?php

namespace App\Livewire\Clients;

use App\Livewire\Traits\Pagination\CanPaginateManually;
use App\Livewire\Traits\Validation\Ownership as ValidateOwnership;
use App\Models\Client;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, CanPaginateManually, ValidateOwnership;

    public int $perPage = 5;

    public function render()
    {
        return view('livewire.clients.index', [
            'clients' => $this->query()
        ]);
    }

    private function query()
    {
        return $this->paginate(Client::queryOwnModels()->get(), $this->perPage);
    }

    public function destroy(int $id)
    {
        $client = $this->validate_ownership($id, Client::class);
        $client->delete();
    }
}
