@props(['products'])

<x-table class="w-full mb-3">
    @forelse ($products as $product)
        <x-table.tr wire:key="{{$product->id}}">
            <td class="p-3 max-w-60 break-words" x-on:click="$dispatch('edit-product', { product_id: {{$product->id}} })">
                {{$product->name}}
            </td>
            <td class="py-3 flex justify-end">
                <flux:button icon="plus" x-on:click="$dispatch('add-product', { id: {{$product->id}} })"/>
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