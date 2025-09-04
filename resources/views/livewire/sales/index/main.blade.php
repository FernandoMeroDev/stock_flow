<div>
    <form wire:submit="downloadSales" class="space-y-6">
        <div>
            <flux:heading size="lg">Ventas</flux:heading>
            <flux:text class="mt-2">Seleccione dos inventarios para calcular las ventas entre ellos.</flux:text>
        </div>

        <div class="flex flex-wrap space-y-2 sm:space-y-0 space-x-0 sm:space-x-2">
            <flux:input wire:model.live="date_from" label="Consultar desde:" type="date" />
            <flux:input wire:model.live="date_to" label="Consultar hasta:" type="date" />
        </div>

        <x-table class="w-full mb-3">
            <x-slot:thead>
                <x-table.th></x-table.th>
                <x-table.th></x-table.th>
                <x-table.th>
                    Inventarios
                </x-table.th>
            </x-slot:thead>

            @forelse ($inventories as $inventory)
                <x-table.tr wire:key="inventory-{{$inventory->id}}">
                    <td class="w-5 px-3">
                        <div class="flex justify-center items-center">
                            <input 
                                type="checkbox" value="{{$inventory->id}}" class="inventory-id-input w-5 h-5"
                                wire:model="inventories_ids.{{$inventory->id}}" id="inventory-id-input-{{$inventory->id}}" 
                            />
                        </div>
                    </td>
                    <td class="w-5 px-3 py-1">
                        <div class="flex space-x-1">
                            <a href="{{route('inventories.edit', $inventory->id)}}">
                                <flux:button icon="pencil"></flux:button>
                            </a>
                            <a href="{{route('inventories.download', $inventory->id)}}">
                                <flux:button icon="arrow-down-tray"></flux:button>
                            </a>
                        </div>
                    </td>
                    <td class="p-3">
                        {{$inventory->saved_at}}
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

        <x-pagination :paginator="$inventories" />

        @script
        <script>
            const limitMaxChecked = (event) => {
                const checked_inputs = Array.from(
                    document.querySelectorAll('.inventory-id-input')
                ).filter(input => input.checked);

                if(checked_inputs.length == 3){
                    // Get checked input Position
                    // 0 => start
                    // 1 => middle
                    // 2 => end
                    let position;
                    for(let i in checked_inputs){
                        let checked_input = checked_inputs[i];
                        if(checked_input.id == event.target.id){
                            position = i;
                            break;
                        }
                    }
                    // If position is middle (1) uncheck input at the end (2)
                    // Else uncheck input at the middle (1)
                    let id = position == 1
                        ? checked_inputs[2].value
                        : checked_inputs[1].value;
                    // Uncheck input manipulating Livewire component's property
                    $wire.inventories_ids[id] = undefined;
                }
            };
            const manipulateEventListeners = (operation) => {
                let inventory_id_inputs = document.querySelectorAll('.inventory-id-input');
                for(input of inventory_id_inputs){
                    if(operation == 'add')
                        input.addEventListener('change', limitMaxChecked);
                    else if(operation == 'remove')
                        input.removeEventListener('change', limitMaxChecked);
                }
            };

            Livewire.hook('morph',  ({ el, component }) => {
                manipulateEventListeners('remove');
            });

            Livewire.hook('morphed',  ({ el, component }) => {
                manipulateEventListeners('add');
            });
            
            manipulateEventListeners('add');
        </script>
        @endscript

        <div>
            <flux:button type="submit" variant="primary">
                Descargar
            </flux:button>
        </div>

        @error('inventories_ids') <p class="text-red-400 mt-4">{{$message}}</p> @enderror
        @error('inventories_ids.*') <p class="text-red-400 mt-4">{{$message}}</p> @enderror
        @error('inventory_a') <p class="text-red-400 mt-4">{{$message}}</p> @enderror
        @error('inventory_b') <p class="text-red-400 mt-4">{{$message}}</p> @enderror
    </form>
</div>
