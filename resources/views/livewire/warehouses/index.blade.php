<div>
    <x-table class="w-full mb-3">
        <x-slot:thead>
            <x-table.th></x-table.th>
            <x-table.th>
                Bodegas
            </x-table.th>
        </x-slot:thead>

        @forelse ($warehouses as $warehouse)
            <x-table.tr>
                <td class="w-5 px-3 py-1">
                    <a href="{{route('warehouses.edit', $warehouse->id)}}">
                        <flux:button icon="pencil"></flux:button>
                    </a>
                </td>
                <td class="p-3">
                    {{$warehouse->name}}
                </td>
            </x-table.tr>
        @empty
            <x-table.tr>
                <td class="p-3">
                    No hay resultados...
                </td>
            </x-table.tr>
        @endforelse
    </x-table>

    <x-pagination :paginator="$warehouses" />

    <form wire:ignore action="{{route('inventories.store')}}" method="POST" class="my-10 space-y-3">
        <div>
            <flux:heading size="lg">Guardar Inventario</flux:heading>
            <flux:text>
                Capture el inventario actual en la base de datos.
            </flux:text>
        </div>

        @csrf

        <flux:input 
            x-data="liveDatetimeInput" x-model="datetime" label="Fecha" type="datetime" name="saved_at" value="{{now()}}"
            x-on:focus="clearInputInterval()"
        />

        <div title="Elimina todos los movimientos." class="mt-2 flex items-center space-x-2">
            <input type="checkbox" name="empty_movements" id="empty_movements_input" class="size-5" />
            <label for="empty_movements_input">Vaciar Movimientos</label>
        </div>

        <flux:button type="submit" variant="primary">
            Guardar
        </flux:button>
    </form>

    <div class="mb-3">
        <flux:heading size="lg">Inventarios</flux:heading>
        <flux:text>
            Consulte los inventarios guardados en la base de datos.
        </flux:text>
    </div>

    <livewire:inventories.index />

    @script
    <script>
        Alpine.data('liveDatetimeInput', () => ({
            interval: null,

            datetime: '',

            init() {
                this.datetime = this.formatDate(new Date());
                this.interval = setInterval(() => {
                    this.datetime = this.formatDate(new Date());
                }, 1000);
            },

            formatDate(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                const hours = String(date.getHours()).padStart(2, '0');
                const minutes = String(date.getMinutes()).padStart(2, '0');
                const seconds = String(date.getSeconds()).padStart(2, '0');
                return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
            },

            clearInputInterval() { clearInterval(this.interval); },
        }));
    </script>
    @endscript
</div>
