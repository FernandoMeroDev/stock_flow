<?php

namespace App\Livewire\Loans;

use App\Models\Loans\Loan;
use Livewire\Component;

class Edit extends Component
{
    public Loan $loan;

    public function mount(Loan $loan)
    {
        $this->loan = $loan;
    }

    public function render()
    {
        return view('livewire.loans.edit');
    }
}
