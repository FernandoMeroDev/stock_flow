<?php

namespace App\Rules\Warehouses\Shelves\Create;

use App\Models\Warehouse;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueNumber implements ValidationRule
{
    private Warehouse $warehouse;

    public function __construct(Warehouse $warehouse)
    {
        $this->warehouse = $warehouse;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        foreach($this->warehouse->shelves as $shelf){
            if($shelf->number == $value)
                $fail("La percha número {$value} ya existe");
        }
    }
}
