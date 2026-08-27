<?php

namespace App\Http\Controllers\Inventories;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Warehouse;

class AuditController extends Controller
{
    public function __invoke()
    {
        $products = Product::orderBy('name')->get();
        $warehouses = Warehouse::all();
        $filename = $this->createFilename();
        $content = "\xEF\xBB\xBFProducto";
        foreach($warehouses as $warehouse){
            $content .= ',Inventario ' . $warehouse->name;
            $content .= ',Kardex ' . $warehouse->name;
            $content .= ',Discrepancia ' . $warehouse->name;
        }
        $content .= "\n";

        /**
         * @var Product
         */
        foreach($products as $product){
            $content .= $product->name;
            foreach($warehouses as $warehouse){
                $inventory_stock = $product->warehouse_existences($warehouse)->sum('count');
                $content .= ',' . $inventory_stock;
                $kardex_stock = $product->stock_in_warehouse($warehouse);
                $content .= ',' . $kardex_stock;
                $content .= ',' . ($inventory_stock - $kardex_stock);
            }
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
        return 'reporte-cuadre-inventario-' . date('Y_m_d_His') . '.csv';
    }
}
