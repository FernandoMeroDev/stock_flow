<?php

namespace App\Http\Requests\Sales\Download;

use Illuminate\Foundation\Http\FormRequest;

class MultipleRequest extends FormRequest
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
            'download_date_from' => 'required|before:download_date_to',
            'download_date_to' => 'required|before_or_equal:today|after:download_date_from'
        ];
    }

    public function attributes(): array
    {
        return [
            'warehouse_id' => 'Bodega',
            'download_date_from' => 'Fecha Desde',
            'download_date_to' => 'Fecha Hasta'
        ];
    }
}
