<?php

namespace App\Models\Movements;

use App\Models\Provider;
use App\Models\Traits\QueryOwnModels;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use SoftDeletes, QueryOwnModels;

    protected $fillable = ['invoice_number', 'provider_id', 'user_id'];

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function movements(): MorphMany
    {
        return $this->morphMany(Movement::class, 'movementable');
    }
}
