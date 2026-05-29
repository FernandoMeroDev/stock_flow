<?php

namespace App\Models\Movements;

use App\Models\Traits\QueryOwnModels;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DisposalDevolution extends Model
{
    use SoftDeletes, QueryOwnModels;

    protected $table = 'disposal_devolutions';

    protected $fillable = [
        'user_id'
    ];

    public function get_readable_type_name(): string
    {
        return 'Devolución de Venta';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function movements(): MorphMany
    {
        return $this->morphMany(Movement::class, 'movementable');
    }

    public function disposals(): HasMany
    {
        return $this->hasMany(Disposal::class, 'devolution_id');
    }
}
