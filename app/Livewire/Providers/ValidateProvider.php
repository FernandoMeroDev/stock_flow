<?php

namespace App\Livewire\Providers;

use App\Models\Provider;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait ValidateProvider
{
    protected User $user;

    public function boot()
    {
        $this->user = Auth::user();
    }

    protected function validate_provider_id(int $id): Provider
    {
        if($provider = Provider::find($id)){
            if( ! $this->user->hasRole('Administrador')){
                if($provider->user_id !== $this->user->id){
                    abort(403);
                }
            }
            return $provider;
        }
        abort(404);
    }
}