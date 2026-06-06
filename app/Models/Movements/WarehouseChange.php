<?php

namespace App\Models\Movements;

use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WarehouseChange extends Model
{
    protected $table = 'warehouse_changes';

    protected $fillable = [
        'user_id',
        'warehouse_id',
        'outcome_id',
        'income_id',
    ];

    public function get_readable_type_name()
    {
        if(is_null($this->outcome_id))
            return 'Salida de Cambio de Bodega';
        else
            return 'Entrada de Cambio de Bodega';
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function income(): BelongsTo
    {
        return $this->belongsTo(WarehouseChange::class, 'income_id');
    }

    public function outcome(): BelongsTo
    {
        return $this->belongsTo(WarehouseChange::class, 'outcome_id');
    }
}
