<div>
    <form action="{{route('sales.calc')}}" class="space-y-4">
        <div>
            <flux:heading size="lg">Calcular Ventas</flux:heading>
            <flux:text class="mt-2">Seleccione dos inventarios para calcular las ventas entre ellos y descargar un reporte.</flux:text>
        </div>

        <div class="grid gap-2 sm:grid-cols-2">
            <flux:input wire:model.live="date_from" label="Consultar desde:" type="date" />
            <flux:input wire:model.live="date_to" label="Consultar hasta:" type="date" />
        </div>

        <x-table class="w-full mb-3">
            <x-slot:thead>
                <x-table.th text-align="center">Desde</x-table.th>
                <x-table.th text-align="center">Hasta</x-table.th>
                <x-table.th>Fecha</x-table.th>
            </x-slot:thead>

            @forelse ($inventories as $inventory)
                <x-table.tr wire:key="inventory-{{$inventory->id}}">
                    <td class="p-3 text-center">
                        <input 
                            type="radio" name="inventory_a" class="w-5 h-5"
                            value="{{$inventory->id}}"
                        />
                    </td>
                    <td class="p-3 text-center">
                        <input 
                            type="radio" name="inventory_b" class="w-5 h-5"
                            value="{{$inventory->id}}"
                        />
                    </td>
                    <td class="p-3">
                        <div class="flex flex-wrap items-center space-x-1">
                            <a href="{{route('inventories.edit', $inventory->id)}}">
                                <flux:button icon="pencil"></flux:button>
                            </a>
                            <a href="{{route('inventories.download', $inventory->id)}}">
                                <flux:button icon="arrow-down-tray"></flux:button>
                            </a>
                            <span>{{$inventory->saved_at}}</span>
                        </div>
                    </td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <td></td>
                    <td></td>
                    <td class="p-3">
                        No hay resultados...
                    </td>
                </x-table.tr>
            @endforelse
        </x-table>

        <x-pagination :paginator="$inventories" />

        <div>
            <flux:button type="submit" variant="primary">
                Descargar
            </flux:button>
        </div>

        @error('inventory_a') <p class="text-red-400 mt-4">{{$message}}</p> @enderror
        @error('inventory_b') <p class="text-red-400 mt-4">{{$message}}</p> @enderror
    </form>
</div>
