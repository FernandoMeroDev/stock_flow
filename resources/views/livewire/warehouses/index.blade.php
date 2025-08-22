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
</div>
