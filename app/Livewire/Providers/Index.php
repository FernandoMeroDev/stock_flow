<?php

namespace App\Livewire\Providers;

use App\Livewire\Traits\Pagination\CanPaginateManually;
use App\Models\Provider;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, CanPaginateManually, ValidateProvider;

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
        if($this->user->hasRole('Administrador')){
            $providers = Provider::get();
        } else {
            $providers = Provider::where('user_id', $this->user->id)->get();
        }
        return $this->paginate($providers, $this->perPage);
    }

    public function destroy(int $id)
    {
        $provider = $this->validate_provider_id($id);
        $provider->delete();
    }
}
