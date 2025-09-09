<?php

namespace App\Livewire\Sales\Day;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Warehouse;
use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use IntlDateFormatter;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Main extends Component
{
    use WithPagination, WithoutUrlPagination, Navigable;

    #[Locked]
    public int $warehouse_id;

    #[Url]
    public ?string $date;

    public array $sales = [];

    private LengthAwarePaginator $paginator;

    protected function rules(): array
    {
        return [
            'date' => 'required|date_format:Y-m-d|before_or_equal:today',
            'sales.*.id' => 'required|exists:sales,id',
            'sales.*.count' => 'required|integer|min:0|max:9999',
            'sales.*.cash' => 'required|decimal:0,2|min:0.01|max:99999.99'
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'sales.*.id' => 'Producto #:position',
            'sales.*.count' => 'Cantidad',
            'sales.*.cash' => 'Precio',
        ];
    }

    protected function messages(): array
    {
        return [
            'date.before_or_equal' => 'La fecha debe ser al menos hoy.',
        ];
    }

    public function mount(Warehouse $warehouse)
    {
        $this->warehouse_id = $warehouse->id;
        if( ! isset($this->date) )
            $this->date = date('Y-m-d');
        $this->paginator = $this->query();
        $this->reset('sales');
        $this->setSales();
    }

    public function render()
    {
        $this->paginator = $this->query();
        $this->reset('sales');
        $this->setSales();
        return view('livewire.sales.day.main', [
            'warehouse_name' => Warehouse::find($this->warehouse_id)->name,
            'date_formatted' => $this->formatDate(),
            'paginator' => $this->paginator,
            'total_cash' => $this->totalCash()
        ]);
    }

    private function setSales(): void
    {
        foreach($this->paginator->getCollection() as $sale){
            $this->sales[] = [
                'id' => $sale->id,
                'name' => $sale->name,
                'count' => $sale->count,
                'cash' => $sale->cash,
            ];
        }
    }

    public function updated($name, $value)
    {
        $this->validate();
        $attribute = null;
        if(Str::contains($name, 'sales.')){
            if(Str::contains($name, '.count'))
                $attribute = 'count';
            else if((Str::contains($name, '.cash')))
                $attribute = 'cash';
        }
        if($attribute){
            $key = Str::between($name, 'sales.', '.' . $attribute);
            $id = $this->sales[$key]['id'];
            if($sale = Sale::find($id))
                $sale->update([$attribute => $value]);
        }
    }

    private function querySales(): Builder
    {
        return Sale::where(
            'warehouse_id', $this->warehouse_id
        )->whereBetween('saved_at', [
            $this->date . ' 00:00:01',
            $this->date . ' 23:59:59',
        ]);
    }

    private function query(): LengthAwarePaginator
    {
        return $this->querySales()
            ->orderBy('saved_at', 'desc')
            ->orderBy('id', 'desc')->paginate(20, pageName: 'sales_page');
    }

    private function totalCash(): float
    {
        return $this->querySales()->sum('cash');
    }

    private function formatDate(): string
    {
        $formatter = new IntlDateFormatter('es_ES', IntlDateFormatter::FULL, IntlDateFormatter::NONE);
        return ucfirst($formatter->format(new DateTime($this->date)));
    }

    public function addProduct($primary_key, $type)
    {
        $this->validate();
        $product = null;
        switch($type){
            case 'id':
                $product = Product::find($primary_key); break;
            case 'barcode':
                $product = Product::where('barcode', $primary_key)->first(); break;
        }
        if($product){
            $present =  $this->date == date('Y-m-d');
            $last_sale = $this->querySales()->orderBy('saved_at', 'desc');
            if( ! $present ) $last_sale->orderBy('id', 'desc');
            $last_sale = $last_sale->first();
            if($last_sale?->product_id == $product->id){
                // Update Sale adding
                $last_sale->update([
                    'count' => $last_sale->count + 1,
                    'cash' => $last_sale->cash + ($product->price)
                ]);
            } else {
                $this->createSale($product, $present);
            }
        }
    }

    private function createSale(Product $product, bool $present): void
    {
        $datetime =  $present ? now() : date('Y-m-d', strtotime($this->date)) . ' 23:59:59';
        Sale::create([
            'name' => $product->name,
            'count' => 1,
            'cash' => $product->price ?? 0.01,
            'saved_at' => $datetime,
            'product_id' => $product->id,
            'warehouse_id' => $this->warehouse_id
        ]);
    }

    public function deleteSale($id)
    {
        if($sale = Sale::find($id))
            $sale->delete();
    }
}
