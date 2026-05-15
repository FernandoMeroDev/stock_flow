<?php

namespace App\Models\Movements;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Balance extends Model
{
    use SoftDeletes;

    protected $fillable = ['units', 'unitary_price', 'movement_id'];

    protected function totalPrice(): Attribute
    {
        return Attribute::make(
            get: function(mixed $value, array $attributes) {
                return $attributes['units'] * $attributes['unitary_price'];
            }
        );
    }

    public function movement(): BelongsTo
    {
        return $this->belongsTo(Movement::class);
    }
}
