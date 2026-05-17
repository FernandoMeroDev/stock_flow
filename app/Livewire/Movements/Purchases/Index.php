<?php

namespace App\Livewire\Movements\Purchases;

use App\Livewire\Traits\Pagination\CanPaginateManually;
use App\Livewire\Traits\SetUser;
use App\Models\Movements\Purchase;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, CanPaginateManually, SetUser;

    public int $perPage = 15;

    public function render()
    {
        return view('livewire.movements.purchases.index', [
            'purchases' => $this->query()
        ]);
    }

    protected function query()
    {
        return $this->paginate(
            Purchase::queryOwnModels()->orderBy('created_at', 'desc')->get(),
            $this->perPage
        );
    }
}
