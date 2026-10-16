<?php

namespace App\Livewire\Loans\Payments;

use App\Models\Loans\Loan;
use App\Models\Loans\Payments\Payment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public Loan $loan;

    public float $new_payment_value;

    public function mount(Loan $loan)
    {
        $this->loan = $loan;
    }

    public function render()
    {
        return view('livewire.loans.payments.index');
    }

    public function addPayment(): void
    {
        $this->validate([
            'new_payment_value' => [
                "required", "decimal:0,2", "min:0.01", 
                "max:" . $this->loan->remaining_float_value()
            ]
        ]);
        Payment::create([
            'loan_id' => $this->loan->id,
            'value' => $this->new_payment_value,
            'created_by_id' => Auth::user()->id,
        ]);
        if($this->loan->remaining_float_value() == 0){
            $this->finishPayment();
        }
    }

    public function completePay()
    {
        Payment::create([
            'loan_id' => $this->loan->id,
            'value' => $this->loan->remaining_float_value(),
            'created_by_id' => Auth::user()->id,
        ]);
        $this->finishPayment();
    }

    protected function finishPayment(): void
    {
        $this->loan->finished = true;
        $this->loan->save();
        $this->redirect(route('loans.edit', $this->loan->id));
    }
}
