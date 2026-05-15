<?php

namespace App\Models\Movements;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Balance extends Model
{
    use SoftDeletes;

    protected $fillable = ['units', 'unitary_price', 'movement_id'];

    public function movement(): BelongsTo
    {
        return $this->belongsTo(Movement::class);
    }
}
