<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Presentation extends Model
{
    use SoftDeletes;

    protected $table = 'presentations';

    protected $fillable = [
        'name',
        'units',
        'price',
        'base',
        'product_id'
    ];

    public $timestamps = false;

    public function complete_name(): string
    {
        return $this->name . ' ' . $this->product->name;
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
