<?php

namespace App\Models;

use App\Models\Shelves\Level;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'img', 'barcode'];

    public function levels(): BelongsToMany
    {
        return $this->belongsToMany(Level::class)->withPivot(['count']);
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }
}
