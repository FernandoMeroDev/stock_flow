<?php

namespace App\Livewire\Loans;

use App\Models\Loans\Loan;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Index extends Component
{
    #[Validate('required|bool')]
    public bool $finished = false;

    public function render()
    {
        return view('livewire.loans.index', [
            'loans' => Loan::where('finished', $this->finished)->orderBy('dead_line', 'asc')->paginate(15)
        ]);
    }
}
