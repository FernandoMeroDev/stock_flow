<div>
    <div class="flex mb-3">
        <flux:input type="text" placeholder="Buscar" wire:model.live="search" class="mr-2" />
        <a href="{{route('purchases.create')}}">
            <flux:button>Nuevo</flux:button>
        </a>
    </div>

    <x-table class="w-full mb-3">
        <x-slot:thead>
            <x-table.th></x-table.th>
            <x-table.th>
                Compras
            </x-table.th>
            <x-table.th>
                Cantidad
            </x-table.th>
        </x-slot:thead>

        @forelse ($purchases as $purchase)
            <x-table.tr>
                <td class="w-5 px-3 py-1">
                    <flux:button icon="pencil" x-on:click="$dispatch('edit-purchase', { id: {{$purchase->id}} })"></flux:button>
                </td>
                <td class="p-3">
                    {{$purchase->product_name}}
                </td>
                <td class="p-3">
                    {{$purchase->count}}
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

    <x-pagination :paginator="$purchases" />

    <livewire:purchases.edit @edited="$refresh" />
</div>