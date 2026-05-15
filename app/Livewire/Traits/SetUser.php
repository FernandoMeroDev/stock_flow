<?php

namespace App\Livewire\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait SetUser
{
    protected User $user;

    public function boot()
    {
        $this->user = Auth::user();
    }
}