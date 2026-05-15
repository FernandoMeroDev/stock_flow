<?php

namespace App\Models;

use App\Models\Movements\Purchase;
use App\Models\Traits\QueryOwnModels;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Provider extends Model
{
    use SoftDeletes, QueryOwnModels;

    protected $fillable = ['name', 'user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function provider(): HasOne
    {
        return $this->hasOne(Purchase::class);
    }
}
