<?php

namespace App\Livewire\Movements\Disposals\Devolutions;

use App\Models\Movements\Balance;
use App\Models\Movements\Disposal;
use App\Models\Movements\DisposalDevolution;
use App\Models\Movements\Movement;
use App\Models\ProductWarehouse;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Create extends Component
{
    use WithPagination;

    public $warehouse_id = 0;

    public string $date_from;

    public string $date_to;

    public array $selected = [];

    public function mount()
    {
        $this->date_from = Carbon::now()->subWeek()->format('Y-m-d');
        $this->date_to = Carbon::now()->format('Y-m-d');
    }

    public function render()
    {
        return view('livewire.movements.disposals.devolutions.create', [
            'warehouses' => Warehouse::all(),
            'disposals' => $this->query(),
            'selectedDisposals' => $this->querySelected()
        ]);
    }

    protected function query()
    {
        return Disposal::where('devolution_id', null)
            ->whereNotIn('id', $this->selected)
            ->where('warehouse_id', $this->warehouse_id)
            ->where('created_at', '>=', $this->date_from . ' 00:00:00')
            ->where('created_at', '<=', $this->date_to . ' 23:59:59')
            ->paginate(15);
    }

    protected function querySelected()
    {
        return Disposal::whereIn('id', $this->selected)->get();
    }

    public function add(int $id)
    {
        $this->selected[] = $id;
    }

    public function remove(int $key)
    {
        unset($this->selected[$key]);
    }

    public function createDevolution()
    {
        $devolution = DisposalDevolution::create([
            'user_id' => Auth::user()->id
        ]);
        foreach($this->selected as $id){
            $disposal = Disposal::find($id);
            $disposal->update([
                'devolution_id' => $devolution->id
            ]);
            $oldMovement = $disposal->movements->get(0);
            $product = $oldMovement->product;
            $presentation = $oldMovement->presentation;
            $lastMovement = $product->movements()
                ->where('warehouse_id', $this->warehouse_id)
                ->orderBy('created_at', 'desc')
                ->orderBy('id', 'desc')
                ->first();
            $count = $oldMovement->count;
            $unitary_price = $lastMovement->unitary_price;
            $movement = Movement::create([
                'count' => $count,
                'unitary_price' => $unitary_price,
                'movementable_id' => $devolution->id,
                'movementable_type' => DisposalDevolution::class,
                'presentation_id' => $presentation->id,
                'product_id' => $presentation->product->id,
                'warehouse_id' => $this->warehouse_id
            ]);
            Balance::create([
                'units' => $lastMovement->balance->units + $count,
                'unitary_price' => $unitary_price,
                'movement_id' => $movement->id
            ]);
            $productWarehouse = ProductWarehouse::where('product_id', $presentation->product->id)
                ->where('warehouse_id', $this->warehouse_id)
                ->first();
            $productWarehouse->update([
                'stock' => $productWarehouse->stock + $count
            ]);
        }
        $this->reset('selected');
    }
}
