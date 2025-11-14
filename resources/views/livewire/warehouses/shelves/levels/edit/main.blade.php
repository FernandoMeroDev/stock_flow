<div>  
<form wire:submit="update">  
    <x-warehouses.shelves.edit.navigation :level="$form->level" class="mb-3" />

    <x-table class="w-full mb-4">
        <x-slot:thead>
            <x-table.th>
                <div class="flex justify-between items-center">
                    <span>
                        Productos
                        <span wire:ignore id="unsaved_changes_indicator" class="hidden text-red-400">
                            (Cambios sin guardar)
                        </span>
                    </span>
                    @if( ! $drag_and_drop_enabled)
                        <flux:button icon="arrow-path-rounded-square" id="ableDragAndDropButton" class="livewireActionButton"></flux:button>
                    @endif
                </div>
            </x-table.th>
        </x-slot:thead>

        @php $i = 1 @endphp
        @forelse ($form->products as $id => $product)
            <x-table.tr wire:key="product-{{$id}}" class="productRowDraggable" id="product-{{$id}}">
                <td class="max-w-full p-3">
                    <div class="grid gap-2 grid-cols-1 sm:grid-cols-2">
                        <button
                            class="inline-block break-words open-product-edit-button"
                            @if( ! $drag_and_drop_enabled)
                                x-on:click.prevent="
                                    if( ! $event.target.disabled ) 
                                        $dispatch('edit-product', { product_id: {{$id}} })
                                "
                            @else x-on:click.prevent @endif
                        >
                            <span class="text-red-500">{{$i}}</span> .- {{$product['name']}}
                        </button>
                        <div class="flex mt-1 w-full justify-between sm:w-auto">
                            <flux:input
                                placeholder="Cantidad"
                                value="{{$product['count']}}"
                                x-on:change="$wire.updateCount({{$id}}, $event.target.value)"
                                id="product-{{$id}}"
                                required min="1" max="9999"
                            />
                            @if( ! $drag_and_drop_enabled)
                                <flux:button
                                    class="ml-2 livewireActionButton"
                                    icon="trash"
                                    wire:click="remove({{$id}})"
                                />
                            @endif
                        </div>
                    </div>
                </td>
            </x-table.tr>
            @php $i++ @endphp
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

    <div class="flex justify-between mt-4">
        <flux:button 
            :type="'submit'" 
            variant="primary" 
        >Guardar cambios</flux:button>
        @if( ! $drag_and_drop_enabled)
            <flux:button 
                class="livewireActionButton"
                x-on:click="$wire.empty()"
            >Vaciar</flux:button>
        @endif
    </div>

    @error('*')
    <p class="text-red-400 mt-4">
        {{$message}}
    </p>
    @enderror
</form>

<livewire:products.edit @edited="refresh_products" />

<livewire:warehouses.shelves.levels.edit.shelf-navigation :level="$form->level" />

@script
<script>
    const indicator = document.getElementById('unsaved_changes_indicator');
    const preventUnload = (event) => {
        event.preventDefault();
        event.returnValue = 'Tienes cambios sin guardar, ¿quieres abandonar la página?';
    };
    $js('register_unsaved_changes', () => {
        indicator.classList.remove('hidden');
        window.addEventListener("beforeunload", preventUnload);
    });
    $js('remove_unsaved_changes', () => {
        indicator.classList.add('hidden');
        window.removeEventListener("beforeunload", preventUnload);
    });
</script>
@endscript
</div>
