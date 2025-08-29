<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{
    public static function getTableName(): string
    {
        return (new static)->getTable();
    }
}
