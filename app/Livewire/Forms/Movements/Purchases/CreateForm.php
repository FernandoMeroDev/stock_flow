<?php

namespace App\Livewire\Forms\Movements\Purchases;

use App\Livewire\Traits\Validation\Ownership as ValidateOwnership;
use App\Models\Movements\Balance;
use App\Models\Movements\Movement;
use App\Models\Movements\Purchase;
use App\Models\Presentation;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Form;

class CreateForm extends Form
{
    use ValidateOwnership;

    public string $invoice_number = '';

    public int $provider_id = 0;

    public array $movements = [];

    protected function rules(): array
    {
        return [
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
        $this->validate_ownership($presentation->product_id, Product::class, 'created_by');
        $this->movements[] = [
            'presentation_id' => $presentation->id,
            'count' => 1,
            'unitary_price' => $presentation->price,
        ];
    }

    public function store()
    {
        $this->validate();
        foreach($this->movements as $movement){
            $presentation = Presentation::find($movement['presentation_id']);
            $product = $presentation->product;
            $lastMovement = $product->movements()->orderBy('created_at', 'desc')->first();
            if($lastMovement){
                $this->addNew($lastMovement, $presentation, $movement);
            } else {
                $this->createInitial($presentation, $movement);
            }
        }
        $this->reset();
    }

    protected function addNew(
        Movement $lastMovement,
        Presentation $presentation,
        array $movement,
    )
    {
        $count = $presentation->units * $movement['count'];
        $unitary_price = $movement['unitary_price'] / $presentation->units;
        $total_price = $count * $unitary_price;
        $new_unitary_price = ($lastMovement->balance->total_price + $total_price) / ($lastMovement->balance->units + $count);
        $purchase = Purchase::create([
            'invoice_number' => $this->invoice_number,
            'provider_id' => $this->provider_id,
            'user_id' => Auth::user()->id,
        ]);
        $movement = Movement::create([
            'count' => $count,
            'unitary_price' => $unitary_price,
            'movementable_id' => $purchase->id,
            'movementable_type' => Purchase::class,
            'presentation_id' => $presentation->id,
            'product_id' => $presentation->product->id
        ]);
        Balance::create([
            'units' => $lastMovement->balance->units + $count,
            'unitary_price' => $new_unitary_price,
            'movement_id' => $movement->id
        ]);
    }

    protected function createInitial(
        Presentation $presentation,
        array $movement,
    )
    {
        $purchase = Purchase::create([
            'invoice_number' => $this->invoice_number,
            'provider_id' => $this->provider_id,
            'user_id' => Auth::user()->id,
        ]);
        $movement = Movement::create([
            'count' => $presentation->units * $movement['count'],
            'unitary_price' => $movement['unitary_price'] / $presentation->units,
            'movementable_id' => $purchase->id,
            'movementable_type' => Purchase::class,
            'presentation_id' => $presentation->id,
            'product_id' => $presentation->product->id
        ]);
        Balance::create([
            'units' => $presentation->units * $movement['count'],
            'unitary_price' => $movement['unitary_price'],
            'movement_id' => $movement->id
        ]);
    }
}
