<?php

namespace App\Rules\Sales\Download;

use App\Models\Inventory;
use Closure;
use DateTime;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Validation\Validator;

class SavedAfter implements ValidationRule, ValidatorAwareRule, DataAwareRule
{
    /**
     * The validator instance.
     *
     * @var \Illuminate\Validation\Validator
     */
    protected $validator;

    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected $data = [];

    /**
     * The field that contains previous inventory's id
     */
    public string $field;

    public function __construct(string $field)
    {
        $this->field = $field;
    }

    /**
     * Set the data under validation.
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;
 
        return $this;
    }

    /**
     * Set the current validator.
     */
    public function setValidator(Validator $validator): static
    {
        $this->validator = $validator;
 
        return $this;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if($this->validator->errors()->any())
            return;
        $previous_inventory = Inventory::find($this->data[$this->field]);
        $inventory = Inventory::find($value);
        if(
            (new DateTime($previous_inventory->saved_at))
            >= (new DateTime($inventory->saved_at))
        ){
            $fail('El Inventario A debe ir después del Inventario B');
        }
    }
}
