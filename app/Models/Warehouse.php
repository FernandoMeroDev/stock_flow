<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public $timestamps = false;

    public function shelves(): HasMany
    {
        return $this->hasMany(Shelf::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}
