<?php

namespace App\Models\Shelves;

use App\Models\Model;
use App\Models\Product;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelProduct extends Model
{
    protected $table = 'level_product';

    protected $fillable = ['count', 'level_id', 'product_id'];

    public $timestamps = false;

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
