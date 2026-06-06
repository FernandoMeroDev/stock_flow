<?php

namespace App\Livewire\Forms\Movements\WarehouseChanges;

use App\Models\Movements\Balance;
use App\Models\Movements\Movement;
use App\Models\Movements\WarehouseChange;
use App\Models\Presentation;
use App\Models\ProductWarehouse;
use App\Rules\Products\HasStock;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CreateForm extends Form
{
    public int $warehouse_id = 0;

    public int $warehouse_to_id = 0;

    public array $movements = [];

    protected function rules(): array
    {
        return [
            'warehouse_id' => 'required|exists:warehouses,id',
            'warehouse_to_id' => 'required|exists:warehouses,id|different:warehouse_id',
            'movements' => ['required', 'array', 'max:50', new HasStock()],
            'movements.*' => 'required|array:presentation_id,count|size:2',
            'movements.*.presentation_id' => 'required|exists:presentations,id',
            'movements.*.count' => 'required|integer|max:9999',
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'warehouse_id' => 'Desde la Bodega',
            'warehouse_to_id' => 'Hasta la Bodega',
            'movements' => 'Movimientos',
            'movements.*' => 'Movimiento #:position',
            'movements.*.presentation_id' => 'Producto',
            'movements.*.count' => 'Cantidad',
        ];
    }

    public function addProduct(Presentation $presentation)
    {
        $this->movements[] = [
            'presentation_id' => $presentation->id,
            'count' => 1,
        ];
    }
    
    public function store()
    {
        $this->validate();
        # Crear Salida
        $outcome = WarehouseChange::create([
            'user_id' => Auth::user()->id,
            'warehouse_id' => $this->warehouse_id,
        ]);
        foreach($this->movements as $movement){
            $presentation = Presentation::find($movement['presentation_id']);
            $product = $presentation->product;
            $lastMovement = $product->movements()
                ->where('warehouse_id', $this->warehouse_id)
                ->orderBy('created_at', 'desc')
                ->orderBy('id', 'desc')->first();
            $this->createOutcomeMovement($outcome, $lastMovement, $presentation, $movement);
        }
        # Crear Ingreso
        $income = WarehouseChange::create([
            'user_id' => Auth::user()->id,
            'warehouse_id' => $this->warehouse_to_id,
            'outcome_id' => $outcome->id
        ]);
        foreach($this->movements as $movement){
            $presentation = Presentation::find($movement['presentation_id']);
            $product = $presentation->product;
            $lastMovement = $product->movements()
                ->where('warehouse_id', $this->warehouse_to_id)
                ->orderBy('created_at', 'desc')
                ->orderBy('id', 'desc')->first();
            $this->createIncomeMovement($income, $lastMovement, $presentation, $movement);
        }
        $outcome->update([
            'income_id' => $income->id
        ]);
    }

    protected function createIncomeMovement(
        WarehouseChange $income,
        Movement $lastMovement,
        Presentation $presentation,
        array $movement,
    )
    {
        $count = $presentation->units * $movement['count'];
        $movement = Movement::create([
            'count' => $count,
            'unitary_price' => $lastMovement->unitary_price,
            'movementable_id' => $income->id,
            'movementable_type' => WarehouseChange::class,
            'presentation_id' => $presentation->id,
            'product_id' => $presentation->product->id,
            'warehouse_id' => $this->warehouse_to_id
        ]);
        Balance::create([
            'units' => $lastMovement->balance->units + $count,
            'unitary_price' => $lastMovement->balance->unitary_price,
            'movement_id' => $movement->id
        ]);
        $productWarehouse = ProductWarehouse::where('product_id', $presentation->product->id)
            ->where('warehouse_id', $this->warehouse_to_id)
            ->first();
        $productWarehouse->update([
            'stock' => $productWarehouse->stock + $count
        ]);
    }
    
    protected function createOutcomeMovement(
        WarehouseChange $outcome,
        Movement $lastMovement,
        Presentation $presentation,
        array $movement
    )
    {
        $count = $presentation->units * $movement['count'];
        $movement = Movement::create([
            'count' => $count,
            'unitary_price' => $lastMovement->balance->unitary_price,
            'movementable_id' => $outcome->id,
            'movementable_type' => WarehouseChange::class,
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
