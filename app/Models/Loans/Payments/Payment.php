<?php

namespace App\Models\Loans\Payments;

use App\Models\Loans\Loan;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $table = 'loan_payments';

    protected $fillable = [
        'loan_id',
        'value',
        'created_by_id',
        'updated_by_id',
    ];

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    public function created_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function updated_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }
}
