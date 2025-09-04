<x-layouts.app :title="__('Dashboard')">
    <div class="flex flex-wrap justify-center sm:justify-evenly">
        <x-dashboard.card :href="route('products.index')">
            <x-svg.box class="w-32 h-32" />
            <p class="text-center">Productos</p>
        </x-dashboard.card>
        <x-dashboard.card :href="route('movements.index')">
            <x-svg.movement class="w-32 h-32" />
            <p class="text-center">Movimientos</p>
        </x-dashboard.card>
        <x-dashboard.card :href="route('sales.index')">
            <x-svg.report-linechart class="w-32 h-32" />
            <p class="text-center">Ventas</p>
        </x-dashboard.card>
        <x-dashboard.card :href="route('inventories.index')">
            <x-svg.report-text class="w-32 h-32" />
            <p class="text-center">Inventarios</p>
        </x-dashboard.card>
        <x-dashboard.card :href="route('warehouses.index')">
            <x-svg.warehouse class="w-32 h-32" />
            <p class="text-center">Bodegas</p>
        </x-dashboard.card>
    </div>
</x-layouts.app>
