<?php

namespace App\Livewire\Providers;

use App\Livewire\Traits\Pagination\CanPaginateManually;
use App\Livewire\Traits\Validation\Ownership as ValidateOwnership;
use App\Models\Provider;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, CanPaginateManually, ValidateOwnership;

    public int $perPage = 5;

    public int $providerid = 0;

    public function render()
    {
        return view('livewire.providers.index', [
            'providers' => $this->query()
        ]);
    }

    private function query()
    {
        return $this->paginate(Provider::queryOwnModels()->get(), $this->perPage);
    }

    public function destroy(int $id)
    {
        $provider = $this->validate_ownership($id, Provider::class);
        $provider->delete();
    }
}
