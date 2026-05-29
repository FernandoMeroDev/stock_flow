<?php

namespace App\Livewire\Forms\Movements\Purchases\Devolutions;

use App\Models\Movements\PurchaseDevolution;
use App\Models\Movements\Balance;
use App\Models\Movements\Movement;
use App\Models\Presentation;
use App\Models\ProductWarehouse;
use App\Rules\Products\HasStock;
use Illuminate\Support\Facades\Auth;
use Livewire\Form;

class CreateForm extends Form
{
    public int $warehouse_id = 0;

    public array $movements = [];

    protected function rules(): array
    {
        return [
            'warehouse_id' => 'required|exists:warehouses,id',
            'movements' => ['required', 'array', 'max:50', new HasStock()],
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
        $purchaseDevolution = PurchaseDevolution::create([
            'user_id' => Auth::user()->id,
        ]);
        foreach($this->movements as $movement){
            $presentation = Presentation::find($movement['presentation_id']);
            $product = $presentation->product;
            $lastMovement = $product->movements()
                ->where('warehouse_id', $this->warehouse_id)
                ->orderBy('created_at', 'desc')
                ->orderBy('id', 'desc')->first();
            $this->createDevolution($purchaseDevolution, $lastMovement, $presentation, $movement);
        }
        $this->resetExcept('warehouse_id', 'provider_id');
    }

    protected function createDevolution(
        PurchaseDevolution $purchaseDevolution,
        Movement $lastMovement,
        Presentation $presentation,
        array $movement
    )
    {
        $count = $presentation->units * $movement['count'];
        $movement = Movement::create([
            'count' => $count,
            'unitary_price' => $lastMovement->balance->unitary_price,
            'movementable_id' => $purchaseDevolution->id,
            'movementable_type' => PurchaseDevolution::class,
            'presentation_id' => $presentation->id,
            'product_id' => $presentation->product->id,
            'warehouse_id' => $this->warehouse_id
        ]);
        Balance::create([
            'units' => $lastMovement->balance->units - $movement->count,
            'unitary_price' => $movement->unitary_price,
            'movement_id' => $movement->id
        ]);
        $productWarehouse = ProductWarehouse::where('product_id', $presentation->product->id)
            ->where('warehouse_id', $this->warehouse_id)
            ->first();
        $productWarehouse->update([
            'stock' => $productWarehouse->stock - $movement->count
        ]);
    }
}
