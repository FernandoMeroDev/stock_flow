<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryProduct extends Model
{
    protected $table = 'inventory_product';

    protected $fillable = [
        'name',
        'price',
        'incoming_count',
        'outgoing_count',
        'product_id',
        'inventory_id'
    ];

    public $timestamps = false;

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }
}
