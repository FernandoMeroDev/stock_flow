<div>
    <livewire:sales.index.calc-sales />

    <form action="{{route('sales.download')}}" class="space-y-4">
        <div class="mt-10">
            <flux:heading size="lg">Consultar Ventas</flux:heading>
            <flux:text class="mt-2">Seleccione dos días para descargar un reporte de las ventas en ellos.</flux:text>
        </div>

        <div class="grid gap-2 sm:grid-cols-2">
            <flux:input wire:model.live="date_from" label="Consultar desde:" type="date" />
            <flux:input wire:model.live="date_to" label="Consultar hasta:" type="date" />
        </div>

        <x-table class="w-full">
            <x-slot:thead>
                <x-table.th>Desde</x-table.th>
                <x-table.th>Hasta</x-table.th>
                <x-table.th>
                    Fecha
                </x-table.th>
            </x-slot:thead>

            @forelse($sales as $sale)
                <x-table.tr>
                    <x-table.th>
                        <input type="radio" name="download_date_from" value="{{$sale->date}}" />
                    </x-table.th>
                    <x-table.th>
                        <input type="radio" name="download_date_to" value="{{$sale->date}}" />
                    </x-table.th>
                    <x-table.th>
                        <div class="flex flex-wrap items-center space-x-1">
                            <a href="#">
                                <flux:button icon="pencil"></flux:button>
                            </a>
                            <a href="#">
                                <flux:button icon="arrow-down-tray"></flux:button>
                            </a>
                            <span>{{$sale->date}}</span>
                        </div>
                    </x-table.th>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <td class="p-3">
                        No hay resultados...
                    </td>
                </x-table.tr>
            @endforelse
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
