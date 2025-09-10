<?php

namespace App\Http\Requests\Sales\Download;

use Illuminate\Foundation\Http\FormRequest;

class SingleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'warehouse_id' => 'required|exists:warehouses,id',
            'date' => 'required|before_or_equal:today'
        ];
    }

    public function attributes(): array
    {
        return [
            'warehouse_id' => 'Bodega',
            'date' => 'Fecha',
        ];
    }
}
