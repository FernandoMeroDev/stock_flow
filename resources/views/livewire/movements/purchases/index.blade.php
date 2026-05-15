<div class="space-y-3">
    <flux:heading size="xl">
        Compras
    </flux:heading>

    <div>
        <flux:button href="{{route('dashboard')}}">
            Agregar Nueva
        </flux:button>
    </div>

    <x-table class="w-full">
        <x-slot:thead>
            <x-table.th>
                Fecha
            </x-table.th>
            <x-table.th>
                Factura
            </x-table.th>
            <x-table.th>
                Creado Por
            </x-table.th>
        </x-slot:thead>
        @forelse($purchases as $purchase)
            <x-table.tr wire:key="{{$purchase->id}}">
                <td class="p-3">
                    {{$purchase->created_at}}
                </td>
                <td class="p-3">
                    {{$purchase->invoice_number}}
                </td>
                <td class="p-3">
                    {{$purchase->user->name}}
                </td>
            </x-table.tr>
        @empty
            <x-table.tr wire:key="0">
                <td class="p-3">
                    No se encontraron resultados...
                </td>
                <td></td>
                <td></td>
            </x-table.tr>
        @endforelse
    </x-table>

    <x-pagination :paginator="$purchases" perPage="5" />
</div>
