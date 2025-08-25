<?php

namespace App\Models;

use App\Models\Shelves\Level;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shelf extends Model
{
    protected $table = 'shelves';

    protected $fillable = ['number', 'warehouse_id'];

    public $timestamps = false;

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function levels(): HasMany
    {
        return $this->hasMany(Level::class, 'shelf_id', 'id');
    }
}
