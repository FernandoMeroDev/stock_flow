<?php

namespace App\Http\Requests\Sales;

use App\Rules\Sales\Download\SavedAfter;
use Illuminate\Foundation\Http\FormRequest;

class DownloadRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'inventory_a' => 'required|integer|exists:inventories,id',
            'inventory_b' => ['required', 'integer', 'exists:inventories,id', new SavedAfter('inventory_a')]
        ];
    }
}
