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
        $sales = Sale::whereBetween('saved_at', [
            $day . ' 00:00:01',
            $day . ' 23:59:59',
        ])->where('warehouse_id', $this->warehouse->id)->get();

        $content = $this->formatDate($day) . ",,\n";

        if($sales->count() < 1){
            $content .= "No hay ventas en este día,,\n";
            $content .= ",,\n";
            return $content;
        }

        $content .= "Productos,Cantidad,Efectivo\n";

        $total_cash = 0;
        foreach($sales as $sale){
            $content .= $sale->name . ',';
            $content .= $sale->count . ',';
            $content .= $sale->cash . "\n";
            $total_cash += $sale->cash;
        }
        $content .= ",Total:,{$total_cash}\n";
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
