<?php

namespace App\Models\Loans;

use App\Models\Presentation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanPresentation extends Model
{
    protected $fillable = [
        'loand_id',
        'presentation_id',
        'count',
        'unitary_price',
    ];

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    public function presentation(): BelongsTo
    {
        return $this->belongsTo(Presentation::class);
    }
}
