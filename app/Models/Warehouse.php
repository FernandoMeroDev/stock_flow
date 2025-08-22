<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    protected $fillable = ['name'];

    public $timestamps = false;

    public function shelves(): HasMany
    {
        return $this->hasMany(Shelf::class);
    }
}
