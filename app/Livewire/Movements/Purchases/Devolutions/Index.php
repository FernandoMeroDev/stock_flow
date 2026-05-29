<?php

namespace App\Livewire\Movements\Purchases\Devolutions;

use App\Livewire\Movements\Purchases\Index as PurchasesIndex;
use App\Models\Movements\PurchaseDevolution;

class Index extends PurchasesIndex
{
    public function render()
    {
        return view('livewire.movements.purchases.devolutions.index', [
            'purchases' => $this->query()
        ]);
    }

    protected function query()
    {
        return $this->paginate(
            PurchaseDevolution::queryOwnModels()->orderBy('created_at', 'desc')->get(),
            $this->perPage
        );
    }
}
