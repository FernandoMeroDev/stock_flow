<?php

namespace App\Livewire\Loans;

use App\Models\Client;
use App\Models\Loans\Loan;
use App\Models\Loans\LoanPresentation;
use App\Models\Movements\Balance;
use App\Models\Movements\Movement;
use App\Models\Presentation;
use App\Models\Product;
use App\Models\ProductWarehouse;
use App\Models\User;
use App\Models\Warehouse;
use App\Rules\Products\HasStock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Create extends Component
{
    public int $warehouse_id;

    public int $client_id;

    public int $guarantor_id;

    public string $dead_line;

    public array $presentations = [];

    public function render()
    {
        return view('livewire.loans.create', [
            'warehouses' => Warehouse::all(),
            'clients' => Client::all(),
            'users' => User::all(),
        ]);
    }

    public function store(): void
    {
        $this->validate([
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'client_id' => ['required', 'exists:clients,id'],
            'guarantor_id' => ['required', 'exists:users,id'],
            'dead_line' => [
                'required',
                'date',
                Rule::date()->after(today()),
                Rule::date()->beforeOrEqual(today()->addDays(30))
            ],
            'presentations' => ['required', 'array', 'max:50', new HasStock()],
            'presentations.*' => ['required', 'array:presentation_id,count,total_price'],
            'presentations.*.presentation_id' => ['required', 'exists:presentations,id'],
            'presentations.*.count' => ['required', 'integer', 'min:1'],
        ]);
        $user = Auth::user();
        $loan = Loan::create([
            'lender_id' => $user->id,
            'client_id' => $this->client_id,
            'guarantor_id' => $this->guarantor_id,
            'warehouse_id' => $this->warehouse_id,
            'dead_line' => $this->dead_line,
            'finished' => false,
            'created_by_id' => $user->id,
        ]);
        foreach($this->presentations as $presentation_data){
            $presentation = Presentation::find($presentation_data['presentation_id']);
            $lastMovement = Movement::where('product_id', $presentation->product->id)
                ->where('warehouse_id', $this->warehouse_id)
                ->orderBy('created_at', 'desc')
                ->first();
            if(is_null($lastMovement)){
                abort(500, 'Error ultimo Movimiento no encontrado.'); $loan->delete();
            } elseif(
                ($lastMovement->balance->units - $presentation->units) < 0
            ) {
                abort(400, 'El saldo no puede ser menor a cero.'); $loan->delete();
            }
            $loanPresentation = LoanPresentation::create([
                'loan_id' => $loan->id,
                'presentation_id' => $presentation->id,
                'count' => $presentation_data['count'],
                'unitary_price' => $presentation-> price,
            ]);
            $movement = Movement::create([
                'count' => $presentation->units * $presentation_data['count'],
                'unitary_price' => $lastMovement->balance->unitary_price,
                'movementable_id' => $loanPresentation->id,
                'movementable_type' => LoanPresentation::class,
                'presentation_id' => $presentation->id,
                'product_id' => $presentation->product->id,
                'warehouse_id' => $this->warehouse_id
            ]);
            $balance = Balance::create([
                'units' => $lastMovement->balance->units - $movement->count,
                'unitary_price' => $movement->unitary_price,
                'movement_id' => $movement->id
            ]);
            $productWarehouse = ProductWarehouse::where('product_id', $presentation->product->id)
                ->where('warehouse_id', $this->warehouse_id)
                ->first();
            $productWarehouse->update([
                'stock' => $balance->units
            ]);
        }
        $this->redirect(route('loans.index'));
    }

    public function addPresentation(mixed $primary_key, string $type): void
    {
        $product = null;
        switch($type){
            case 'id':
                $presentation = Presentation::find($primary_key); break;
            case 'barcode':
                $product = Product::where('barcode', $primary_key)->first();
                $presentation = $product?->presentations->get(0); break;
        }
        if($presentation){
            if(
                $presentation->product
                    ->stock_in_warehouse(
                        Warehouse::find($this->warehouse_id)
                    ) 
                < $presentation->units
            )
                abort(403);
            $this->presentations[] = [
                'presentation_id' => $presentation->id,
                'count' => 1,
                'total_price' => $presentation->price
            ];
        }
    }

    public function removePresentation(int $key)
    {
        unset($this->presentations[$key]);
    }
}
