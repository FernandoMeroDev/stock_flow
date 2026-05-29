<?php

namespace App\Livewire\Forms\Movements\Purchases\Devolutions;

use App\Models\Movements\PurchaseDevolution;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UpdateForm extends Form
{
    public PurchaseDevolution $purchaseDevolution;

    public array $movements = [];

    protected function rules(): array
    {
        return [
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
            'movements' => 'Movimientos',
            'movements.*' => 'Movimiento #:position',
            'movements.*.presentation_id' => 'Producto',
            'movements.*.count' => 'Cantidad',
            'movements.*.unitary_price' => 'Precio Unitario',
        ];
    }

    public function setPurchaseDevolution(PurchaseDevolution $purchaseDevolution)
    {
        $this->purchaseDevolution = $purchaseDevolution;
        foreach($purchaseDevolution->movements as $movement){
            $this->movements[] = [
                'presentation_id' => $movement->presentation_id,
                'count' => $movement->count,
                'unitary_price' => $movement->unitary_price
            ];
        }
    }
}
