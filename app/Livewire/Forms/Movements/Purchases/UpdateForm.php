<?php

namespace App\Livewire\Forms\Movements\Purchases;

use App\Models\Movements\Purchase;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UpdateForm extends Form
{
    public Purchase $purchase;

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

    public function setPurchase(Purchase $purchase)
    {
        $this->purchase = $purchase;
        $this->invoice_number = $purchase->invoice_number;
        $this->provider_id = $purchase->provider_id;
        foreach($purchase->movements as $movement){
            $this->movements[] = [
                'presentation_id' => $movement->presentation_id,
                'count' => $movement->count,
                'unitary_price' => $movement->unitary_price
            ];
        }
    }
}