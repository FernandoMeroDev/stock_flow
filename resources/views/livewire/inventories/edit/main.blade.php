<div class="space-y-3">

    <livewire:inventories.edit.update :$inventory />

    <div class="mt-10">
        <div class="flex items-start justify-between">
            <flux:heading size="lg">Inventario</flux:heading>
            <flux:modal.trigger name="delete-inventory">
                <flux:button variant="danger">
                    Eliminar
                </flux:button>
            </flux:modal.trigger>
        </div>
        <flux:text class="mt-2">Revise o edite la información del inventario.</flux:text>
    </div>

    <flux:modal name="delete-inventory" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Eliminar Inventario</flux:heading>
                <flux:text class="mt-2">¿Está seguro de <strong>eliminar</strong> el inventario y toda su información?</flux:text>
            </div>
            <div class="flex justify-between">
                <flux:button x-on:click="$flux.modal('delete-inventory').close()" variant="primary">Cancelar</flux:button>
                <flux:button wire:click="delete" variant="danger">Eliminar</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:input wire:model.live="search" placeholder="Buscar Producto..." />

    <div class="overflow-x-scroll">
        <x-table class="min-w-3xl w-full">
            <x-slot:thead>
                <x-table.th></x-table.th>
                <x-table.th>CÓDIGO</x-table.th>
                <x-table.th>Producto</x-table.th>
                <x-table.th>Precio</x-table.th>
                <x-table.th>Entradas</x-table.th>
                <x-table.th>Salidas</x-table.th>
                @foreach($warehouses as $warehouse)
                    <x-table.th>{{$warehouse->name}}</x-table.th>
                @endforeach
                <x-table.th>Total</x-table.th>
            </x-slot:thead>

            @forelse($inventory_records as $inventory_record)
                <x-table.tr>
                    <td class="p-3">
                        <a href="#">
                            <flux:button icon="pencil"></flux:button>
                        </a>
                    </td>
                    <td class="p-3">
                        {{$inventory_record->product_id}}
                    </td>
                    <td class="p-3">
                        {{$inventory_record->name}}
                    </td>
                    <td class="p-3">
                        {{$inventory_record->price}}
                    </td>
                    <td class="p-3">
                        {{$inventory_record->incoming_count}}
                    </td>
                    <td class="p-3">
                        {{$inventory_record->outgoing_count}}
                    </td>
                    @foreach($warehouses as $warehouse)
                        <td class="p-3">
                            {{$inventory_record->stocks()->where('warehouse_id', $warehouse->id)->value('count')}}
                        </td>
                    @endforeach
                    <td class="p-3">
                        {{$inventory_record->stocks->sum('count')}}
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
    </div>

    <x-pagination :paginator="$inventory_records" />
</div>
