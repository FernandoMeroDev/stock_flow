<div>  
<form wire:submit="update">  
    <x-warehouses.shelves.edit.navigation :level="$form->level" class="mb-3" />

    <x-table class="w-full mb-4">
        <x-slot:thead>
            <x-table.th>
                <div class="flex justify-between items-center">
                    Productos
                    <flux:button
                        icon="arrow-path-rounded-square" id="ableDragAndDropButton" 
                        disabled class="livewireActionButton"
                    ></flux:button>
                </div>
            </x-table.th>
        </x-slot:thead>

        @forelse ($form->products as $id => $product)
            <x-table.tr wire:key="product-{{$id}}" class="productRowDraggable" id="product-{{$id}}">
                <td class="p-3">
                    <div class="flex flex-wrap items-center sm:justify-between">
                        <button
                            class="open-product-edit-button"
                            x-on:click.prevent="
                                if( ! $event.target.disabled ) 
                                    $dispatch('edit-product', { product_id: {{$id}} })
                            "
                        >
                            {{$product['name']}}
                        </button>
                        <div class="flex mt-1 w-full justify-between sm:w-auto">
                            <flux:input
                                placeholder="Cantidad"
                                value="{{$product['count']}}"
                                x-on:change="$wire.updateCount({{$id}}, $event.target.value)"
                                id="product-{{$id}}"
                                required min="1" max="9999"
                            />
                            <flux:button
                                class="ml-2 livewireActionButton"
                                icon="trash"
                                wire:click="remove({{$id}})"
                            />
                        </div>
                    </div>
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

    <livewire:warehouses.shelves.levels.edit.search-products :products="$form->products" />

    @assets
    @vite(['resources/js/components/warehouses/shelves/levels/edit/drag-and-drop.js'])
    @endassets

    <livewire:products.edit @edited="refresh_products" />

    <div class="flex justify-between mt-4">
        <flux:button 
            type="submit" 
            variant="primary" 
        >Guardar cambios</flux:button>
        <flux:button 
            class="livewireActionButton"
            x-on:click="$wire.empty()"
        >Vaciar</flux:button>
    </div>

    @error('*')
    <p class="text-red-400 mt-4">
        {{$message}}
    </p>
    @enderror
</form>

<livewire:warehouses.shelves.levels.edit.shelf-navigation :level="$form->level" />
</div>
