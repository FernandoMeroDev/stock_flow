<x-layouts.app :title="__('Dashboard')">

    {{-- <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
        <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
    </div> --}}

    <div class="flex flex-wrap justify-center sm:justify-start">
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
