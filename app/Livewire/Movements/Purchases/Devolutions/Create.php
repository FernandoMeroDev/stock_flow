<?php

namespace App\Livewire\Movements\Purchases\Devolutions;

use App\Livewire\Forms\Movements\Purchases\Devolutions\CreateForm;
use App\Livewire\Traits\Pagination\CanPaginateManually;
use App\Livewire\Traits\SetUser;
use App\Models\Presentation;
use App\Models\Provider;
use App\Models\Warehouse;
use Flux\Flux;
use Illuminate\Database\Query\JoinClause;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;


class Create extends Component
{
    use WithPagination, WithoutUrlPagination, CanPaginateManually, SetUser;

    public CreateForm $form;

    public string $search = '';

    public int $presentationsPerPage = 5;

    public function render()
    {
        return view('livewire.movements.purchases.devolutions.create', [
            'warehouses' => Warehouse::all(),
            'providers' => Provider::queryOwnModels()->get(),
            'presentations' => $this->searchPresentations()
        ]);
    }

    public function store()
    {
        $this->form->store();
        Flux::toast('La compra fue guardada!', variant: 'success');
    }

    public function addPresentation(int $id)
    {
        if($presentation = Presentation::find($id)){
            $this->form->addMovement($presentation);
            $this->dispatch('added-movement');
        } else {
            abort(404);
        }
    }

    public function removePresentation(int $key)
    {
        unset($this->form->movements[$key]);
    }

    protected function searchPresentations()
    {
        $warehouse_id = $this->form->warehouse_id;
        $presentations = Presentation::join('products', 'products.id', '=', 'presentations.product_id')
                ->join('product_warehouse', function (JoinClause $join) use ($warehouse_id) {
                    $join->on('product_warehouse.product_id', '=', 'products.id')
                        ->where('product_warehouse.warehouse_id', '=', $warehouse_id);
                })
                ->select('presentations.*', 'product_warehouse.stock as total_stock')
                ->whereRaw(
                    "CONCAT(presentations.name, ' ', products.name) LIKE ?", ["%$this->search%"]
                )->orderBy('name');
        return $this->paginate(
            $presentations->get(), 
            $this->presentationsPerPage, 
            'presentations_page', 
            'searchPresentations'
        );
    }
}