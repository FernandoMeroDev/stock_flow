<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $fillable = [
        'id',
        'issuance_date',
        'number',
        'type'
    ];

    public function movements(): HasMany
    {
        return $this->hasMany(Movement::class);
    }
}
