<?php

namespace App\Http\Controllers\Inventories;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\InventoryProduct;
use App\Models\Warehouse;

class DownloadController extends Controller
{
    public function __invoke(Inventory $inventory)
    {
        // Write File
        $filename = $this->createFilename();

        $content = $this->writeHeaders();

        $inventory_records = InventoryProduct::where('inventory_id', $inventory->id)->orderBy('name')->get();

        $warehouses = Warehouse::all();
        foreach($inventory_records as $inventory_record){
            $content .= $inventory_record->product_id;
            $content .= ",{$inventory_record->name}";
            $content .= ",{$inventory_record->price}";
            $content .= ",{$inventory_record->incoming_count}";
            $content .= ",{$inventory_record->outgoing_count}";
            foreach($warehouses as $warehouse)
                $content .= ',' . $inventory_record->stocks()->where('warehouse_id', $warehouse->id)->value('count');
            $content .= ',' . $inventory_record->stocks->sum('count');
            $content .= "\n";
        }

        // Return Download
        return response($content, status: 200, headers: [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\""
        ]);
    }

    private function createFilename(): string
    {
        return 'reporte-inventario-' . date('Y_m_d_His') . '.csv';
    }

    private function writeHeaders(): string
    {
        $content = "\xEF\xBB\xBFIDENTIFICADOR,Producto,Precio,Entradas,Salidas";

        foreach(Warehouse::all() as $warehouse)
            $content .= ",{$warehouse->name}";

        $content .= ",Total\n";
        return $content;
    }
}
