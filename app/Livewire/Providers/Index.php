<?php

namespace App\Livewire\Providers;

use App\Livewire\Traits\Pagination\CanPaginateManually;
use App\Models\Provider;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, CanPaginateManually;

    public int $perPage = 5;

    private User $user;

    public function boot()
    {
        $this->user = Auth::user();
    }

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
        if($provider = Provider::find($id)){
            if($this->user->hasRole('Administrador')){
                $provider->delete();
            } else {
                if($provider->user_id == $this->user->id){
                    $provider->delete();
                } else
                    abort(403); 
            }
        }
    }
}
