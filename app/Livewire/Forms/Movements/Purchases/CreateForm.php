<?php

namespace App\Livewire\Forms\Movements\Purchases;

use App\Models\Movements\Balance;
use App\Models\Movements\Movement;
use App\Models\Movements\Purchase;
use App\Models\Presentation;
use Illuminate\Support\Facades\Auth;
use Livewire\Form;

class CreateForm extends Form
{
    public int $warehouse_id = 0;

    public string $invoice_number = '';

    public int $provider_id = 0;

    public array $movements = [];

    protected function rules(): array
    {
        return [
            'warehouse_id' => 'required|exists:warehouses,id',
            'invoice_number' => 'required|string|max:255',
            'provider_id' => 'required|exists:providers,id',
            'movements' => 'required|array|max:50',
            'movements.*' => 'required|array:presentation_id,count,unitary_price|size:3',
            'movements.*.presentation_id' => 'required|exists:presentations,id',
            'movements.*.count' => 'required|integer|max:9999',
            'movements.*.unitary_price' => 'required|decimal:0,2|min:0.01|max:999.99',
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'warehouse_id' => 'Bodega',
            'invoice_number' => 'Número de Factura',
            'provider_id' => 'Proveedor',
            'movements' => 'Movimientos',
            'movements.*' => 'Movimiento #:position',
            'movements.*.presentation_id' => 'Producto',
            'movements.*.count' => 'Cantidad',
            'movements.*.unitary_price' => 'Precio Unitario',
        ];
    }

    public function addMovement(Presentation $presentation)
    {
        $this->movements[] = [
            'presentation_id' => $presentation->id,
            'count' => 1,
            'unitary_price' => $presentation->price,
        ];
    }

    public function store()
    {
        $this->validate();
        $purchase = Purchase::create([
            'invoice_number' => $this->invoice_number,
            'provider_id' => $this->provider_id,
            'user_id' => Auth::user()->id,
        ]);
        foreach($this->movements as $movement){
            $presentation = Presentation::find($movement['presentation_id']);
            $product = $presentation->product;
            $lastMovement = $product->movements()
                ->where('warehouse_id', $this->warehouse_id)
                ->orderBy('created_at', 'desc')->first();
            if($lastMovement){
                $this->addNew($purchase, $lastMovement, $presentation, $movement);
            } else {
                $this->createInitial($purchase, $presentation, $movement);
            }
        }
        $this->resetExcept('warehouse_id', 'provider_id');
    }

    protected function addNew(
        Purchase $purchase,
        Movement $lastMovement,
        Presentation $presentation,
        array $movement,
    )
    {
        $count = $presentation->units * $movement['count'];
        $unitary_price = $movement['unitary_price'] / $presentation->units;
        $total_price = $count * $unitary_price;
        $new_unitary_price = ($lastMovement->balance->total_price + $total_price) / ($lastMovement->balance->units + $count);
        $movement = Movement::create([
            'count' => $count,
            'unitary_price' => $unitary_price,
            'movementable_id' => $purchase->id,
            'movementable_type' => Purchase::class,
            'presentation_id' => $presentation->id,
            'product_id' => $presentation->product->id,
            'warehouse_id' => $this->warehouse_id
        ]);
        Balance::create([
            'units' => $lastMovement->balance->units + $count,
            'unitary_price' => $new_unitary_price,
            'movement_id' => $movement->id
        ]);
        $presentation->product->update([
            'total_stock' => $presentation->product->total_stock + $count
        ]);
    }

    protected function createInitial(
        Purchase $purchase,
        Presentation $presentation,
        array $movement,
    )
    {
        $count = $presentation->units * $movement['count'];
        $movement = Movement::create([
            'count' => $count,
            'unitary_price' => $movement['unitary_price'] / $presentation->units,
            'movementable_id' => $purchase->id,
            'movementable_type' => Purchase::class,
            'presentation_id' => $presentation->id,
            'product_id' => $presentation->product->id,
            'warehouse_id' => $this->warehouse_id
        ]);
        Balance::create([
            'units' => $count,
            'unitary_price' => $movement['unitary_price'],
            'movement_id' => $movement->id
        ]);
        $presentation->product->update([
            'total_stock' => $presentation->product->total_stock + $count
        ]);
    }
}
