<?php

namespace App\Models;

use App\Models\Shelves\Level;
use App\Models\Shelves\LevelProduct;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'img', 'barcode', 'price'];

    public function levels(): BelongsToMany
    {
        return $this->belongsToMany(Level::class)->withPivot(['count']);
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    public function warehouse_existences(Warehouse $warehouse): Collection
    {
        $level_product = LevelProduct::getTableName();
        $levels = Level::getTableName();
        $shelves = Shelf::getTableName();
        $warehouses = Warehouse::getTableName();
        return LevelProduct::join($levels, "$levels.id", "=", "$level_product.level_id")
            ->join($shelves, "$shelves.id", "=", "$levels.shelf_id")
            ->join($warehouses, "$warehouses.id", "=", "$shelves.warehouse_id")
            ->select("$shelves.number as shelf_number", "$levels.number as level_number", "$level_product.count")
            ->where("$level_product.product_id", $this->id)
            ->where("$warehouses.id", $warehouse->id)
            ->get();
    }

    public function inventories(): BelongsToMany
    {
        return $this->belongsToMany(Inventory::class);
    }
}
