<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sales\Download\MultipleRequest;
use App\Http\Requests\Sales\Download\SingleRequest;
use App\Models\Sale;
use App\Models\Warehouse;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Str;
use IntlDateFormatter;

class DownloadController extends Controller
{
    private Warehouse $warehouse;

    private IntlDateFormatter $dateFormatter;

    private float $sum_totals_cash = 0;

    public function __construct()
    {
        $this->dateFormatter = new IntlDateFormatter('es_ES', IntlDateFormatter::FULL, IntlDateFormatter::NONE);
    }

    public function multiple(MultipleRequest $request)
    {
        $validated = $request->validated();

        $this->warehouse = Warehouse::find($validated['warehouse_id']);

        $filename = $this->createFilename();

        $content = $this->writeHeaders();

        $dates = $this->getDaysBetween(
            $validated['download_date_from'], $validated['download_date_to']
        );
        foreach($dates as $date){
            $content .= $this->writeSales($date);
        }

        $content = Str::replace('{{sum_totals_cash}}', $this->sum_totals_cash, $content);

        // Return Download
        return response($content, status: 200, headers: [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\""
        ]);
    }

    public function single(SingleRequest $request)
    {
        $validated = $request->validated();

        $this->warehouse = Warehouse::find($validated['warehouse_id']);

        $filename = $this->createFilename();

        $content = $this->writeHeaders();

        $content .= $this->writeSales($validated['date']);

        $content = Str::replace('{{sum_totals_cash}}', $this->sum_totals_cash, $content);

        // Return Download
        return response($content, status: 200, headers: [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\""
        ]);
    }

    private function getDaysBetween(string $dayA, string $dayB): array
    {
        $a = new Carbon($dayA); $b = new Carbon($dayB);
        $dates = [];
        while($a <= $b){
            $dates[] = $a->toDateString();
            $a->addDay();
        }
        return $dates;
    }

    private function writeSales(string $day): string
    {
        $sales = Sale::join('presentations', 'presentations.id', '=', 'sales.presentation_id')
        ->join(
            'products', 'products.id', '=', 'presentations.product_id'
        )->join(
            'cash_boxes', 'products.cash_box_id', '=', 'cash_boxes.id'
        )->select(
            'sales.*',
            'cash_boxes.name as cash_box_name'
        )->whereBetween('saved_at', [
            $day . ' 00:00:01',
            $day . ' 23:59:59',
        ])->where('warehouse_id', $this->warehouse->id)
        ->orderBy('cash_box_name')
        ->orderBy('saved_at')
        ->get();

        $content = '---------------' . $this->formatDate($day) . ",,\n";

        $total_cash = 0;

        if($sales->count() < 1){
            $content .= "No hay ventas en este día,,\n";
            $content .= ",,\n";
            $content .= ",,\n";
            return $content;
        } else {
            $current_cash_box = $sales->get(0)->cash_box_name;
            $cash_box_total = 0;

            $content .= "$current_cash_box,,\n";
            $content .= "Productos,Cantidad,Efectivo\n";

            foreach($sales as $sale){

                if($current_cash_box != $sale->cash_box_name){
                    $content .= ",Total:,{$cash_box_total}\n";
                    $current_cash_box = $sale->cash_box_name;
                    $cash_box_total = 0;
                    $content .= ",,\n";
                    $content .= "$current_cash_box,,\n";
                    $content .= "Productos,Cantidad,Efectivo\n";
                }

                $content .= $sale->name . ',';
                $content .= $sale->count . ',';
                $content .= $sale->cash . "\n";
                $cash_box_total += $sale->cash;
                $total_cash += $sale->cash;
            }
            $content .= ",Total:,{$cash_box_total}\n";
        }

        $content .= ",,\n";
        $content .= ",Total del día:,{$total_cash}\n";
        $content .= ",,\n";
        $content .= ",,\n";

        $this->sum_totals_cash += $total_cash;

        return $content;
    }

    private function writeHeaders(): string
    {
        $content = "\xEF\xBB\xBFBodega: {$this->warehouse->name},,\n";
        $content .= "Efectivo Total: {{sum_totals_cash}},,\n";
        $content .= ",,\n";
        return $content;
    }

    private function createFilename(): string
    {
        return 'reporte-ventas-' . date('Y_m_d_His') . '.csv';
    }

    private function formatDate(string $date): string
    {
        $result =  ucfirst($this->dateFormatter->format(new DateTime($date)));
        return Str::replace(',', '', $result);
    }
}
