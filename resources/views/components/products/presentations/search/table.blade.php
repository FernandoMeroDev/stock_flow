@props(['presentations'])

<x-table class="w-full mb-3">
    @forelse ($presentations as $presentation)
        <x-table.tr wire:key="{{$presentation->id}}">
            <td class="p-3 max-w-60 break-words" x-on:click="$dispatch('edit-product', { product_id: {{$presentation->product->id}} })">
                {{$presentation->complete_name()}}
            </td>
            <td class="py-3 flex justify-end">
                <flux:button icon="plus" x-on:click="$dispatch('add-presentation', { id: {{$presentation->id}} })"/>
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