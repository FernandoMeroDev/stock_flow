<?php

namespace App\Models\Shelves;

use App\Models\Model;
use App\Models\Product;
use App\Models\Shelf;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Level extends Model
{
    protected $fillable = ['number', 'shelf_id'];

    public $timestamps = false;

    public function shelf(): BelongsTo
    {
        return $this->belongsTo(Shelf::class, 'shelf_id', 'id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot(['count']);
    }

    public function calc_products_total_count(): Level
    {
        $count = 0;
        foreach($this->products as $product)
            $count = $count + $product->pivot->count;
        $this->products_total_count = $count;
        return $this;
    }
}
