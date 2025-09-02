<?php

namespace App\Http\Requests\Inventories;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $today = date('Y-m-d H:i:s');
        return [
            'saved_at' => "required|date|before_or_equal:{$today}",
            'empty_movements' => 'sometimes|accepted'
        ];
    }
}
