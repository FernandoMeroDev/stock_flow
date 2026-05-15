<?php

namespace App\Livewire\Traits\Validation;

use App\Livewire\Traits\SetUser;
use Illuminate\Database\Eloquent\Model;

trait Ownership
{
    use SetUser;

    protected function validate_ownership(int $id, string $class): Model
    {
        if($model = $class::find($id)){
            if( ! $this->user->hasRole('Administrador')){
                if($model->user_id !== $this->user->id){
                    abort(403);
                }
            }
            return $model;
        }
        abort(404);
    }
}