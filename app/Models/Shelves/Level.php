<?php

namespace App\Models\Shelves;

use App\Models\Shelf;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Level extends Model
{
    protected $fillable = ['number', 'shelf_id'];

    public $timestamps = false;

    public function shelf(): BelongsTo
    {
        return $this->belongsTo(Shelf::class, 'shelf_id', 'id');
    }
}
