<?php

namespace App\Models\Movements;

use App\Models\Presentation;
use App\Models\Product;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Movement extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'count',
        'unitary_price',
        'movementable_id',
        'movementable_type',
        'presentation_id',
        'product_id',
        'warehouse_id'
    ];

    protected function totalPrice(): Attribute
    {
        return Attribute::make(
            get: function(mixed $value, array $attributes) {
                return $attributes['count'] * $attributes['unitary_price'];
            }
        );
    }
    
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

    public function balance(): HasOne
    {
        return $this->hasOne(Balance::class);
    }
}
