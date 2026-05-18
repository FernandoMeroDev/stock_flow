@props(['presentations'])

<x-table class="w-full mb-3">
    @forelse ($presentations as $presentation)
        @if($presentation->product)
            <x-table.tr>
                <td class="cursor-pointer p-3 max-w-60 break-words" x-on:click="$dispatch('edit-product', { product_id: {{$presentation->product->id}} })">
                    {{$presentation->complete_name()}}
                </td>
                <td class="p-3">
                    <p wire:loading.remove>{{$presentation->total_stock}}</p>
                    <p wire:loading>...</p>
                </td>
                <td class="py-3 flex justify-end">
                    <flux:button 
                        icon="plus" 
                        wire:click="$dispatch('add-presentation', { id: {{$presentation->id}} })"
                        :disabled="$presentation->total_stock < $presentation->units"
                    />
                </td>
            </x-table.tr>
        @endif
    @empty
        <x-table.tr>
            <td class="p-3">
                No hay resultados...
            </td>
        </x-table.tr>
    @endforelse
</x-table>