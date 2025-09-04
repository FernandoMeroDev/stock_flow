<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sales\DownloadRequest;
use App\Models\Inventory;
use App\Models\InventoryProduct;
use App\Models\Warehouse;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class DownloadController extends Controller
{
    private int $warehouses_count;

    private array $CSVheaders;

    public function __construct()
    {
        $warehouses = Warehouse::all();
        $this->warehouses_count = $warehouses->count();
        $this->CSVheaders = [
            'Nombre',
            'Precio',
        ];
        foreach($warehouses as $warehouse)
            $this->CSVheaders[] = $warehouse->name;
        $this->CSVheaders[] = 'Total';
        $this->CSVheaders[] = 'Entradas';
        $this->CSVheaders[] = 'Salidas';
        $this->CSVheaders[] = 'Ventas';
        $this->CSVheaders[] = 'Efectivo Estimado';
    }

    public function __invoke(DownloadRequest $request)
    {
        $validated = $request->validated();
        $inventory_a = Inventory::find($validated['inventory_a']);
        $inventory_b = Inventory::find($validated['inventory_b']);
        $table = $this->queryData($inventory_a, $inventory_b);
        $filename = $this->createFilename();
        $content = $table->count() > 0
            ? $this->buildCSV($table)
            : 'No hay datos en los inventarios seleccionados.';
        return response($content, status: 200, headers: [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\""
        ]);
    }

    /**
     * Creates the table with products' id as Keys
     *  $table = [
     *      'product_id' => [
     *          'saved_at' => [
     *              'name' => '...',
     *              'price' => xx.xxxx,
     *              'stocks' => [
     *                  'warehouse_id' => x,
     *                  'warehouse_id' => x,
     *              ],
     *              'total' => x,
     *              'incoming_count' => x,
     *              'outgoing_count' => x,
     *              'sales' => x,
     *              'estimated_cash' => x
     *          ],
     *          'saved_at' => [
     *              '...'
     *          ],
     *          'saved_at' => [
     *              '...'
     *          ]
     *          ...
     *      ],
     *      'product_id' => [
     *          '...'
     *      ],
     *      ...
     *  ];
     */
    private function queryData(Inventory $a, Inventory $b): Collection
    {
        $inventories = $this->queryInventories($a, $b);
        $table = $this->queryProductIds($a, $b);
        $old_inventory_data = null;
        foreach($table as $product_id => $row){
            foreach($inventories as $i => $inventory){
                $record = $inventory->inventory_product()->where('product_id', $product_id)->first();
                if($record)
                    $inventory_data = $this->calcInventoryData($i, $record, $old_inventory_data);
                else
                    $inventory_data = [
                        'name' => null,
                        'price' => null,
                        'stocks' => null,
                        'total' => null,
                        'incoming_count' => null,
                        'outgoing_count' => null,
                        'sales' => null,
                        'estimated_cash' => null
                    ];
                $row->put($inventory->saved_at, new Collection($inventory_data));
                $old_inventory_data = $inventory_data;
            }
        }
        return $table;
    }

    private function queryProductIds(Inventory $a, Inventory $b): Collection
    {
        $inventory_product = InventoryProduct::getTableName();
        $inventory = Inventory::getTableName();
        $inventory_product_ids = InventoryProduct::join(
            $inventory, "$inventory.id", "=", "$inventory_product.inventory_id"
        )->whereBetween(
            "$inventory.saved_at", [$a->saved_at, $b->saved_at]
        )->distinct()->select(
            "$inventory_product.product_id",
        )->orderBy("product_id")->get();
        $table = new Collection();
        foreach($inventory_product_ids->pluck("product_id") as $id){
            $table->put($id, new Collection());
        }
        return $table;
    }

    private function queryInventories(Inventory $a, Inventory $b): EloquentCollection
    {
        return Inventory::whereBetween(
            "saved_at", [$a->saved_at, $b->saved_at]
        )->orderBy("saved_at")->get();
    }

    private function calcInventoryData(int $i, InventoryProduct $record, ?array $old_inventory_data): array
    {
        $inventory_data = [
            'name' => $record->name,
            'price' => $record->price,
        ];
        $stocks = [];
        foreach($record->stocks as $stock)
            $stocks[$stock->warehouse_id] = $stock->count;
        $inventory_data['stocks'] = $stocks;
        $inventory_data['total'] = $record->stocks->sum('count');
        $inventory_data['incoming_count'] = $record->incoming_count;
        $inventory_data['outgoing_count'] = $record->outgoing_count;
        if($i < 1 || is_null($old_inventory_data['total'])){
            $inventory_data['sales'] = 0;
            $inventory_data['estimated_cash'] = 0;
        } else {
            $inventory_data['sales'] = (
                $old_inventory_data['total']
                - $inventory_data['total'] 
                + $inventory_data['incoming_count'] 
                - $inventory_data['outgoing_count']
            );
            $inventory_data['estimated_cash'] = $inventory_data['sales'] * $inventory_data['price'];
        }
        return $inventory_data;
    }

    private function createFilename(): string
    {
        return 'reporte-ventas-' . date('Y_m_d_His') . '.csv';
    }

    private function buildCSV(Collection $table): string
    {
        $content = $this->writeHeaders($table);
        $content = $this->writeRows($content, $table);
        return $content;
    }

    private function writeHeaders(Collection $table): string
    {
        // ------------- Row 1
        $inventory_columns_count = count($this->CSVheaders);
        $content = "\xEF\xBB\xBF"; // Column 1

        $inventories = $table->first();
        $columns_count = 1 + (($inventory_columns_count + 1) * $inventories->count());

        $dates = $inventories->keys();
        $inserted_date_index = 0;
        $insert_date = 2; // Insert First Date in Column 2
        for($i = 0; $i < $columns_count - 1; $i++){
            $column_count = $i + 2; // Start in Column 2
            if($column_count == $insert_date){
                $content .= ',Inventario ' . $dates->get($inserted_date_index);
                $inserted_date_index += 1;
                $insert_date += $inventory_columns_count + 1; // Next insert Date position
            } else
                $content .= ',';
        }

        $content .= "\n";

        // ------------- Row 2
        $content .= 'IDENTIFICADOR';
        for($i = 0; $i < $inventories->count(); $i++){
            for($j = 0; $j < $inventory_columns_count + 1; $j++){
                if(isset($this->CSVheaders[$j]))
                    $content .= ',' . $this->CSVheaders[$j];
                else
                    $content .= ',';
            }
        }

        $content .= "\n";

        return $content;
    }

    private function writeRows(string $content, Collection $table): string
    {
        // ------------- Row n
        foreach($table as $product_id => $row){
            $content .= $product_id; // Column 1
            foreach($row as $saved_at => $inventory){
                $content .= ',' . $this->checkNullData($inventory->get('name'));
                $content .= ',' . $this->checkNullData($inventory->get('price'));
                if($inventory->get('stocks'))
                    foreach($inventory->get('stocks') as $warehouse_id => $count)
                        $content .= ',' . $count;
                else 
                    for($i = 0; $i < $this->warehouses_count; $i++)
                        $content .= ',' . $this->checkNullData(null);
                $content .= ',' . $this->checkNullData($inventory->get('total'));
                $content .= ',' . $this->checkNullData($inventory->get('incoming_count'));
                $content .= ',' . $this->checkNullData($inventory->get('outgoing_count'));
                $content .= ',' . $this->checkNullData($inventory->get('sales'));
                $content .= ',' . $this->checkNullData($inventory->get('estimated_cash'));
                $content .= ',';
            }
            $content .= "\n";
        }
        return $content;
    }

    private function checkNullData($value): mixed
    {
        return is_null($value) ? 'SIN DATOS' : $value;
    }
}
