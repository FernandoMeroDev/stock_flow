<div class="space-y-3">
    <flux:heading size="xl">
        Kardex
    </flux:heading>

    <flux:text>
        {{$product->name}}
    </flux:text>
    
    <flux:select label="Bodega" wire:model.live="warehouse_id">
        @foreach($warehouses as $warehouse)
            <flux:select.option :selected="$warehouse->id == $warehouse_id" value="{{$warehouse->id}}">{{$warehouse->name}}</flux:select.option>
        @endforeach
    </flux:select>

    <x-table class="w-full">
        <x-slot:thead>
            <x-table.th>Fecha</x-table.th>
            <x-table.th>Descripción</x-table.th>
            <x-table.th class="border-r-1 border-zinc-200 dark:border-zinc-700">Tipo</x-table.th>
            <x-table.th>Cantidad</x-table.th>
            <x-table.th>P. Unitario</x-table.th>
            <x-table.th class="border-r-1 border-zinc-200 dark:border-zinc-700">P. Total</x-table.th>
            <x-table.th>Cantidad</x-table.th>
            <x-table.th>P. Unitario</x-table.th>
            <x-table.th class="border-r-1 border-zinc-200 dark:border-zinc-700">P. Total</x-table.th>
        </x-slot:thead>

        @forelse($movements as $movement)
            <x-table.tr>
                <td class="p-3">
                    {{$movement->created_at}}
                </td>
                <td class="p-3">
                    {{$movement->presentation->complete_name()}}
                </td>
                <td class="p-3 border-r-1 border-zinc-200 dark:border-zinc-700">
                    {{$movement->movementable->get_readable_type_name()}}
                </td>
                <td class="p-3">
                    {{$movement->count}}
                </td>
                <td class="p-3">
                    ${{$movement->unitary_price}}
                </td>
                <td class="p-3 border-r-1 border-zinc-200 dark:border-zinc-700">
                    ${{$movement->total_price}}
                </td>
                <td class="p-3">
                    {{$movement->balance->units}}
                </td>
                <td class="p-3">
                    ${{$movement->balance->unitary_price}}
                </td>
                <td class="p-3 border-r-1 border-zinc-200 dark:border-zinc-700">
                    ${{$movement->balance->total_price}}
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

    <x-pagination :paginator="$movements" :$perPage />
</div>
