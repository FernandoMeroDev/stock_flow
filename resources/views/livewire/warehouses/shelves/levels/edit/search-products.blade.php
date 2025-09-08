<div>
    <div x-data="{ render: true }" x-on:enabled-drag-and-drop.window="render = false">
        <div x-show="render">

            <flux:input 
                label="Agregar Productos" placeholder="Buscar por nombre..."
                wire:model.live.debounce.300ms="search"
            />

            <x-table class="w-full mb-4">
                @forelse ($searchedProducts as $product)
                    <x-table.tr wire:key="{{$product->id}}">
                        <td class="p-3 max-w-60 break-words">
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

            <x-pagination :paginator="$searchedProducts" />
        </div>
    </div>
</div>
