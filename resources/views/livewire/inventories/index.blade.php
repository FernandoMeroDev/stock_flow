<div>
    <div class="space-y-6">
        <div class="flex flex-wrap space-y-2 sm:space-y-0 space-x-0 sm:space-x-2">
            <flux:input wire:model.live="date_from" label="Consultar desde:" type="date" />
            <flux:input wire:model.live="date_to" label="Consultar hasta:" type="date" />
        </div>

        <x-table class="w-full mb-3">
            <x-slot:thead>
                <x-table.th></x-table.th>
                <x-table.th>
                    Inventarios
                </x-table.th>
            </x-slot:thead>

            @forelse ($inventories as $inventory)
                <x-table.tr>
                    <td class="w-5 px-3 py-1">
                        <div class="flex space-x-1">
                            <a href="{{route('inventories.edit', $inventory->id)}}">
                                <flux:button icon="pencil"></flux:button>
                            </a>
                            <a href="#">
                                <flux:button icon="arrow-down-tray"></flux:button>
                            </a>
                        </div>
                    </td>
                    <td class="p-3">
                        {{$inventory->saved_at}}
                    </td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <td></td>
                    <td class="p-3">
                        No hay resultados...
                    </td>
                </x-table.tr>
            @endforelse
        </x-table>

        <x-pagination :paginator="$inventories" />
    </div>
</div>
