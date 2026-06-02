<div class="space-y-3">
    <flux:heading size="xl">
        Registrar Devolucion de Ventas
    </flux:heading>

    <form wire:submit="createDevolution" x-data="{ warehouse_selected: false }">
        <flux:select wire:ignore x-on:change="$el.disabled = true; warehouse_selected = true" label="Bodega" wire:model.live="warehouse_id">
            <flux:select.option value="0">Seleccione...</flux:select.option>
            @foreach($warehouses as $warehouse)
                <flux:select.option value="{{$warehouse->id}}">{{$warehouse->name}}</flux:select.option>
            @endforeach
        </flux:select>

        <div x-show="warehouse_selected" class="space-y-3 my-6">
            <flux:label>
                Seleccionar Ventas
            </flux:label>
            
            <div class="flex">
                <flux:input label="Desde" wire:model.live="date_from" type="date" />
                <div class="w-3"></div>
                <flux:input label="Hasta" wire:model.live="date_to" type="date" />
            </div>

            <x-table class="w-full">
                <x-slot:thead>
                    <x-table.th></x-table.th>
                    <x-table.th>
                        Fecha
                    </x-table.th>
                    <x-table.th>
                        Creado Por
                    </x-table.th>
                    <x-table.th>
                        Producto
                    </x-table.th>
                    <x-table.th>
                        Cantidad
                    </x-table.th>
                    <x-table.th>
                        Precio
                    </x-table.th>
                    <x-table.th>
                        Total
                    </x-table.th>
                </x-slot:thead>
                @forelse($disposals as $disposal)
                    <x-table.tr wire:key="disposal-{{$disposal->id}}">
                        <td class="p-3">
                            <flux:button icon="plus" wire:click="add({{$disposal->id}})" />
                        </td>
                        <td class="p-3">
                            {{$disposal->created_at}}
                        </td>
                        <td class="p-3">
                            {{$disposal->user->name}}
                        </td>
                        <td class="p-3">
                            {{$disposal->movements->get(0)->presentation()->withTrashed()->first()->complete_name()}}
                        </td>
                        <td class="p-3">
                            {{$disposal->movements->get(0)->count}}
                        </td>
                        <td class="p-3">
                            ${{$disposal->movements->get(0)->unitary_price}}
                        </td>
                        
                        <td class="p-3">
                            ${{$disposal->movements->get(0)->count * $disposal->movements->get(0)->unitary_price}}
                        </td>
                    </x-table.tr>
                @empty
                    <x-table.tr wire:key="0">
                        <td class="p-3">
                            No se encontraron resultados...
                        </td>
                    </x-table.tr>
                @endforelse
            </x-table>

            {{ $disposals->links() }}
        </div>

        <flux:label>
            Ventas a devolver
        </flux:label>

        <x-table class="w-full">
            <x-slot:thead>
                <x-table.th></x-table.th>
                <x-table.th>
                    Fecha
                </x-table.th>
                <x-table.th>
                    Creado Por
                </x-table.th>
                <x-table.th>
                    Producto
                </x-table.th>
                <x-table.th>
                    Cantidad
                </x-table.th>
                <x-table.th>
                    Precio
                </x-table.th>
                <x-table.th>
                    Total
                </x-table.th>
            </x-slot:thead>
            @forelse($selectedDisposals as $key => $disposal)
                <x-table.tr wire:key="seleceted-disposal-{{$disposal->id}}">
                    <td class="p-3">
                        <flux:button icon="trash" wire:click="remove({{$key}})" />
                    </td>
                    <td class="p-3">
                        {{$disposal->created_at}}
                    </td>
                    <td class="p-3">
                        {{$disposal->user->name}}
                    </td>
                    <td class="p-3">
                        {{$disposal->movements->get(0)->presentation->complete_name()}}
                    </td>
                    <td class="p-3">
                        {{$disposal->movements->get(0)->count}}
                    </td>
                    <td class="p-3">
                        ${{$disposal->movements->get(0)->unitary_price}}
                    </td>
                    
                    <td class="p-3">
                        ${{$disposal->movements->get(0)->count * $disposal->movements->get(0)->unitary_price}}
                    </td>
                </x-table.tr>
            @empty
                <x-table.tr wire:key="0">
                    <td class="p-3">
                        No se encontraron resultados...
                    </td>
                </x-table.tr>
            @endforelse
        </x-table>

        <flux:button type="submit" variant="primary">
            Guardar
        </flux:button>
    </form>
</div>
