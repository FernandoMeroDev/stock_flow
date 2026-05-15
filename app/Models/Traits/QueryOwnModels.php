<?php
namespace App\Models\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait QueryOwnModels
{
    public static function getUser(): User
    {
        return Auth::user();
    }

    public static function queryOwnModels(): Builder
    {
        $user = static::getUser();
        if($user->hasRole('Administrador')){
            $builder = static::select('*');
        } else {
            $builder = static::where('user_id', $user->id);
        }
        return $builder;
    }
}