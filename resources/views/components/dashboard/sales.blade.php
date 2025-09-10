@props(['warehouseName'])

@use('App\Models\Warehouse')

@if($warehouse = Warehouse::where('name', $warehouseName)->first())
    <x-dashboard.card :href="route('sales.day', [
        'warehouse' => $warehouse->id,
        'day' => date('Y-m-d')
    ])">
        <x-svg.billing class="w-32 h-32" />
        <p class="text-center">Ventas {{$warehouseName}}</p>
    </x-dashboard.card>
@endif