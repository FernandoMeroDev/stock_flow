<?php

namespace App\Models\Movements;

use App\Models\Presentation;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Movement extends Model
{
    protected $fillable = [
        'count',
        'unitary_price',
        'movementable_id',
        'movementable_type',
        'provider_id',
        'product_id'
    ];
    
    public function presentation(): BelongsTo
    {
        return $this->belongsTo(Presentation::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function movementable(): MorphTo
    {
        return $this->morphTo();
    }
}
