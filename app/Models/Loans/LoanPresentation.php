<?php

namespace App\Models\Loans;

use App\Models\Presentation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanPresentation extends Model
{
    protected $table = 'loan_presentation';

    protected $fillable = [
        'loan_id',
        'presentation_id',
        'count',
        'unitary_price',
    ];

    public function get_readable_type_name(): string
    {
        return 'Venta a préstamo';
    }

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    public function presentation(): BelongsTo
    {
        return $this->belongsTo(Presentation::class);
    }
}
