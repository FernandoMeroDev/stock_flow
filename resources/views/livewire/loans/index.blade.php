<div>
    <div class="flex items-center justify-between mb-6">
        <flux:field variant="inline">
            <flux:label>Préstamos finalizados</flux:label>
            <flux:switch wire:model.live="finished" />
            <flux:error name="finished" />
        </flux:field>

        <flux:button :href="route('loans.create')" variant='primary'>
            Nuevo
        </flux:button>
    </div>

    {{-- Let all your things have their places; let each part of your business have its time. - Benjamin Franklin --}}
    <x-table class="w-full mb-3">
        <x-slot:thead>
            <x-table.th>Creación</x-table.th>
            <x-table.th>Préstamista</x-table.th>
            <x-table.th>Garante</x-table.th>
            <x-table.th>Cliente</x-table.th>
            <x-table.th class="!text-right">Valor Total</x-table.th>
            <x-table.th>Límite de Pago</x-table.th>
        </x-slot:thead>

        @forelse ($loans as $loan)
            <x-table.tr x-on:click="open('{{route('loans.edit', $loan->id)}}')" :bg="$loan->statusColor()" wire:key="{{$loan->id}}">
                <td class="p-3">
                    {{$loan->created_at}}
                </td>
                <td class="p-3">
                    {{$loan->lender->name}}
                </td>
                <td class="p-3">
                    {{$loan->guarantor->name}}
                </td>
                <td class="p-3">
                    {{$loan->client->name}}
                </td>
                <td class="p-3 text-right">
                    ${{$loan->total_value()}}
                </td>
                <td class="p-3">
                    {{$loan->dead_line}}
                </td>
            </x-table.tr>
        @empty
            <x-table.tr>
                <td class="p-3">
                    No hay resultados...
                </td>
                <td></td><td></td><td></td><td></td>
            </x-table.tr>
        @endforelse
    </x-table>

    {{$loans->links()}}
</div>
