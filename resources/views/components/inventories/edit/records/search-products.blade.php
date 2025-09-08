@props(['products'])

<div class="space-y-2">
    <flux:input wire:model.live="search" placeholder="Buscar Producto..." label="Buscar Producto" />

    <x-table class="w-full">
        @forelse ($products as $product)
            <x-table.tr>
                <td class="p-3 max-w-60 break-words">
                    {{$product->name}}
                </td>
                <td class="py-3 flex justify-end">
                    <flux:button icon="plus" wire:click="setProduct({{$product->id}})" />
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

    <x-pagination :paginator="$products" />
</div>