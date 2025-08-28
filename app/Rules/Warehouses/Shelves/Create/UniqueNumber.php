<?php

namespace App\Rules\Warehouses\Shelves\Create;

use App\Models\Warehouse;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueNumber implements ValidationRule
{
    private Warehouse $warehouse;

    private ?int $ignore_id;

    public function __construct(Warehouse $warehouse, ?int $except = null)
    {
        $this->warehouse = $warehouse;
        $this->ignore_id = $except;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        foreach($this->warehouse->shelves as $shelf){
            if( ! is_null($this->ignore_id) )
                if($shelf->id == $this->ignore_id) continue;
            if($shelf->number == $value)
                $fail("La percha número {$value} ya existe");
        }
    }
}
