<?php

namespace App\Models\Loans;

use App\Models\Client;
use App\Models\Loans\Payments\Payment;
use App\Models\User;
use App\Models\Warehouse;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Loan extends Model
{
    protected $fillable = [
        'lender_id',
        'client_id',
        'guarantor_id',
        'dead_line',
        'finished',
        'warehouse_id',
        'created_by_id',
        'updated_by_id',
    ];

    /**
     * Calc total value derivated from product presentations
     *  */
    public function total_value(): string
    {
        return number_format($this->total_float_value(), 2, '.');
    }

    public function total_float_value(): float
    {
        $total = 0.0;
        foreach($this->loanPresentation as $loanPresentation){
            $total += $loanPresentation->unitary_price * $loanPresentation->count;
        }
        return $total;
    }

    public function remaining_float_value(): float
    {
        return $this->total_float_value() - $this->payments()->sum('value');;
    }

    public function remaining_value(): string
    {
        return number_format($this->remaining_float_value(), 2, '.');
    }

    public function paid_value(): string
    {
        return number_format($this->total_float_value() - $this->remaining_float_value(), 2, '.');
    }

    public function statusColor(): string 
    {
        // Obtenemos la fecha actual a las 00:00:00 para comparar solo días
        $hoy = new DateTime('today');
        
        // Convertimos la fecha ingresada a un objeto DateTime
        $objetivo = new DateTime($this->dead_line);
        $objetivo->setTime(0, 0, 0);

        if($this->finished) return '';
        
        // Si la fecha ya pasó
        if ($objetivo < $hoy) {
            return 'bg-red-500';
        }
        
        // Calculamos la diferencia en días
        $diferencia = $hoy->diff($objetivo);
        $diasFaltantes = $diferencia->days;
        
        // Evaluamos los días restantes
        if ($diasFaltantes <= 3) {
            return 'bg-orange-700';
        } elseif ($diasFaltantes <= 7) {
            return 'bg-yellow-600';
        } else {
            return '';
        }
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function loanPresentation(): HasMany
    {
        return $this->hasMany(LoanPresentation::class);
    }

    public function lender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lender_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function guarantor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guarantor_id');
    }

    public function created_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function updated_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }
}
