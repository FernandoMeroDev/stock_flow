<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventory extends Model
{
    protected $table = 'inventories';

    protected $fillable = ['saved_at'];

    public $timestamps = false;

    public function product(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    public function inventory_product(): HasMany
    {
        return $this->hasMany(InventoryProduct::class);
    }
}
