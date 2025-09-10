@use('App\Models\Warehouse')
<div>
    <livewire:sales.index.calc-sales />

    <hr class="my-10" />

    <form action="{{route('sales.download')}}" class="space-y-4">
        <div>
            <flux:heading size="lg">Consultar Ventas</flux:heading>
            <flux:text class="mt-2">Seleccione dos días para descargar un reporte de las ventas en ellos.</flux:text>
        </div>

        <div>
            <flux:radio.group wire:model.live="warehouse_id" label="Bodega">
                @foreach(Warehouse::all() as $warehouse)
                    <flux:radio value="{{$warehouse->id}}" label="{{$warehouse->name}}" />
                @endforeach
            </flux:radio.group>
        </div>

        <div class="grid gap-2 sm:grid-cols-2">
            <flux:input wire:model.live="date_from" label="Consultar desde:" type="date" />
            <flux:input wire:model.live="date_to" label="Consultar hasta:" type="date" />
        </div>

        <x-table class="w-full">
            <x-slot:thead>
                <x-table.th text-align="center">Desde</x-table.th>
                <x-table.th text-align="center">Hasta</x-table.th>
                <x-table.th>Fecha</x-table.th>
            </x-slot:thead>

            @forelse($sales as $key => $sale)
                <x-table.tr>
                    <td class="p-3 text-center">
                        <input 
                            type="radio" name="download_date_from" class="w-5 h-5"
                            value="{{$sale->date}}"
                        />
                    </td>
                    <td class="p-3 text-center">
                        <input 
                            type="radio" name="download_date_to" class="w-5 h-5"
                            value="{{$sale->date}}"
                        />
                    </td>
                    <td class="p-3">
                        <div class="flex flex-wrap items-center space-x-1">
                            <a href="{{route('sales.day', [
                                'warehouse' => $warehouse_id,
                                'date' => date('Y-m-d', strtotime($sale->date))
                            ])}}">
                                <flux:button icon="pencil"></flux:button>
                            </a>
                            <a href="#">
                                <flux:button icon="arrow-down-tray"></flux:button>
                            </a>
                            <span>{{$sale->date}} 00:00:00</span>
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
            @if($warehouse_id)
                <x-table.tr>
                    <td></td>
                    <td></td>
                    <td class="p-3 flex items-center">
                        <a href="{{route('sales.day', $warehouse_id)}}" class="mr-1">
                            <flux:button icon="plus"></flux:button>
                        </a>
                        Nuevo Registro
                    </td>
                </x-table.tr>
            @endif
        </x-table>

        <x-pagination :paginator="$sales" />

        <div>
            <flux:button type="submit" variant="primary">
                Descargar
            </flux:button>
        </div>

        @error('download_date_from') <p class="text-red-400 mt-4">{{$message}}</p> @enderror
        @error('download_date_to') <p class="text-red-400 mt-4">{{$message}}</p> @enderror
    </form>
</div>
